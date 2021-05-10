<?php
namespace core\app\classes\module_base;

/**
 * Abstract module_db class.
 * 
 * Used to add commit and rollback commands to module db classes
 *
 * @abstract
 * @package     module_base
 * @author      Martin O'Dee <martin@iow.com.au>
 * @copyright   Martin O'Dee 22 August 2019
 */
abstract class module_db {
    
    protected $db;
    protected $user_id; //the id of the user
    
    public function __construct($location)
    {   
        //instanciate the correct database
        $db_ns = NS_APP_DRIVERS.'\\db\\db_mysql';
        $this->db = $db_ns::getInstance($location);
        $this->user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0 ;
        return;
    }
    
    //!autocommit function
    
    public function commitOff()
    {
        $this->db->commitOff();
        return;
    }

    public function startTransaction()
    {
        $this->db->commitOff();
        return;
    }
    
    public function commit()
    {
        $this->db->commit();
        return;
    }
    
    public function rollback()
    {
        $this->db->rollback();
        return;
    }
    
    public function commitOn()
    {
        $this->db->commitOn();
        return;
    }

    public function filter ( $request, $columns, &$bindings )
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = $this->pluck( $columns, 'dt' , false);

        if ( isset($request['search']) && $request['search']['value'] != '' ) {
            $str = $request['search']['value'];

            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );

                $column = $columns[ $columnIdx ];
                if ( $requestColumn['searchable'] == 'true' ) {
                    //$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $globalSearch[] = self::generateColumn($column['db'])." LIKE "."'%".$str."%'";
                }
            }
        }

        // Individual column filtering
        if ( isset( $request['columns'] ) ) {
            for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];

                $str = $requestColumn['search']['value'];

                if ( $requestColumn['searchable'] == 'true' &&
                    $str != '' ) {
                    //$binding = self::bind( $bindings, '%'.$str.'%', PDO::PARAM_STR );
                    $columnSearch[] = self::generateColumn($column['db'])." LIKE "."'%".$str."%'";
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if ( count( $globalSearch ) ) {
            $where = '('.implode(' OR ', $globalSearch).')';
        }

        if ( count( $columnSearch ) ) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where .' AND '. implode(' AND ', $columnSearch);
        }

        if ( $where !== '' ) {
            $where = 'WHERE '.$where;
        }
        return $where;
    }

    public function pluck ( $a, $prop, $column_status = true, $alias = true )
    {
        $out = array();

        for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
            $col = $column_status ? self::generateColumn($a[$i][$prop]) : $a[$i][$prop];

            if($alias){
                if(isset($a[$i]['as'])){
                    $col .= ' as '.$a[$i]['as'];
                }
            }

            $out[] = $col;
        }

        return $out;
    }
    
    static function generateColumn($data){
        return $data;
        //$col = explode('.',$data);
        //  $col = implode('`.`',$col);
        // '`'.$col.'`';

    }

    /**
     * Paging
     *
     * Validate request that only allowed for the query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $additionals, incase need additioanl request to receive
     *  @return bool
     */
    public function validateRequest ( $request, $additionals = [])
    {
        $required_request = ['draw','columns','order','start','length','search'];
        $merged = array_merge($required_request, $additionals);

        if (array_diff_key($request, array_flip($merged))) {
            die('You should not pass');
            return false;
        }
        return true;
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    public function limit ( $request, $columns )
    {
        $limit = '';

        if ( isset($request['start']) && $request['length'] != -1 ) {
            $limit = "LIMIT ".intval($request['start']).", ".intval($request['length']);
        }

        return $limit;
    }


    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public function order ( $request, $columns )
    {
        $order = '';

        if ( isset($request['order']) && count($request['order']) ) {
            $orderBy = array();
            $dtColumns = $this->pluck( $columns, 'dt',false, false );
            for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search( $requestColumn['data'], $dtColumns );
                $column = $columns[ $columnIdx ];
                if ( $requestColumn['orderable'] == 'true' ) {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = self::generateColumn($column['db']).' '.$dir;
                }
            }

            if ( count( $orderBy ) ) {
                $order = 'ORDER BY '.implode(', ', $orderBy);
            }
        }

        return $order;
    }

    public function data_output ( $columns, $data )
    {
        $out = array();
        for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
            $row = array();

            for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
                $column = $columns[$j];

                // Is there a formatter?
                if ( isset( $column['formatter'] ) ) {
                    $row[ $column['dt'] ] = $column['formatter']( $data[$i][ str_replace('`','',explode('.',$column['db'])[1]) ], $data[$i] );
                }
                else {
                    if(isset($column['as'])){
                        $row[ $column['dt'] ] = $data[$i][ $column['as'] ];
                    }else{
                        $row[ $column['dt'] ] = $data[$i][ isset(explode('.',$columns[$j]['db'])[1]) ? str_replace('`','',explode('.',$columns[$j]['db'])[1]) : explode('.',$columns[$j]['db'])[0] ];
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }


}
?>