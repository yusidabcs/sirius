<?php

namespace core\modules\recruitment\models\common;

/**
 * Final recruitment db class.
 *
 * @final
 * @package        recruitment
 * @author        Martin O'Dee <martin@iow.com.au>
 * @copyright    Martin O'Dee 23 Nov 2018
 */
final class db extends \core\app\classes\module_base\module_db
{

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    public function getSummaryRecruitment($id)
    {
        $profile_common = new \core\modules\personal\models\common\common;
        $profile_common = new \core\modules\personal\models\common\common;
        $profile_common = new \core\modules\personal\models\common\common;
        $profile_common = new \core\modules\personal\models\common\common;

        return $profile_common->getProfileInfo($id);
    }

    public function getAllRecruitmentInMonth($ent = false)
    {
        $out = [];
        $sql = 'SELECT list_date.EventDate, COUNT(address_book.address_book_id) FROM address_book
                    
                    RIGHT JOIN (SELECT EventDate
                        FROM
                        (
                            SELECT MAKEDATE(YEAR(NOW()),1) +
                            INTERVAL (MONTH(NOW())-1) MONTH +
                            INTERVAL daynum DAY EventDate
                            FROM
                            (
                                SELECT t*10+u daynum FROM
                                (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3) A,
                                (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
                                UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
                                UNION SELECT 8 UNION SELECT 9) B ORDER BY daynum
                            ) AA
                        ) AA WHERE MONTH(EventDate) = MONTH(NOW())) list_date
                        
                        on list_date.EventDate = DATE(address_book.created_on)
                        
                        
                        
                        GROUP BY list_date.EventDate
                        
                        ORDER BY list_date.EventDate ASC
                        
                       ';

        $stmt = $this->db->prepare($sql);

        $stmt->bind_result($date, $total);
        $stmt->execute();

        while ($stmt->fetch()) {
            $out[] = array(
                'date' => $date,
                'total' => $total
            );

        }
        $stmt->close();
        return $out;
    }

    public function getAllRecruitment($ent = false)
    {
        $request = $_POST;
        $table = 'address_book';

        $primaryKey = 'address_book.address_book_id';
        $columns = array(
            array('db' => 'address_book_per.title', 'dt' => 'title'),
            array('db' => 'address_book_per.middle_names', 'dt' => 'middle_names'),
            array('db' => 'address_book.number_given_name', 'dt' => 'number_given_name'),
            array('db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name'),
            array('db' => 'address_book.main_email', 'dt' => 'main_email'),
            array('db' => 'address_book.created_on', 'dt' => 'created_on'),
            array('db' => 'address_book_address.country', 'dt' => 'country'),
            array('db' => 'address_book_connection.connection_id', 'dt' => 'partner_id'),
            array('db' => 'address_book.created_by', 'dt' => 'created_by'),
            array('db' => 'partner_lep.connection_id', 'as' => 'partner_lep_id', 'dt' => 'partner_lep_id'),
            array('db' => 'partner.entity_family_name', 'as' => 'partner_name', 'dt' => 'partner_name'),
            array('db' => 'partner_lep_name.entity_family_name', 'as' => 'partner_lep_name', 'dt' => 'partner_lep_name'),

            array('db' => 'address_book.address_book_id', 'dt' => 'address_book_id'),
            array('db' => 'personal_verification.status', 'dt' => 'status'),
            array('db' => 'personal_verification.verification_info', 'dt' => 'info'),

            array('db' => 'job_premium_service.pstatus', 'dt' => 'premium_status'),
            array('db' => 'job_premium_service.filename', 'dt' => 'premium_file'),
            array('db' => 'job_premium_service.type', 'dt' => 'premium_type'),
            array('db' => 'job_premium_service.sending_on', 'dt' => 'sending_on', 'formatter' => function ($d, $row) {
                return date('M jS Y h:i:s', strtotime($d));
            }),
            array('db' => 'job_premium_service.verified_by', 'dt' => 'verified_by'),

        );


        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);

        $join = 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`="lp"';
        $join .= 'LEFT JOIN `address_book_connection` as `partner_lep` ON `address_book`.`address_book_id` = `partner_lep`.`address_book_id` AND `partner_lep`.`connection_type`="lep"';
        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner_lep_name` ON `partner_lep`.`connection_id` = `partner_lep_name`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_per` ON `address_book`.`address_book_id` = `address_book_per`.`address_book_id` ';
        $join .= 'LEFT JOIN `personal_verification` ON `personal_verification`.`id` = (
            SELECT
                 `id`
            FROM 
                `personal_verification`
            WHERE
                 `address_book_id` = `address_book`.address_book_id
            ORDER BY modified_on DESC
            LIMIT 1
         )';
        $join .= 'LEFT JOIN 
            (
                SELECT
                    `job_premium_service`.`address_book_id`,
                    `job_premium_service`.`type`,
                    `job_premium_service`.`status` as `pstatus`,
                    `job_premium_service`.`filename`,
                    `job_premium_service`.`sending_on`,
                    `job_premium_service`.`verified_by`,
                    CONCAT(`address_book`.`entity_family_name`," ",`address_book`.`number_given_name`) as `sending_by`
                FROM 
                    `job_premium_service`
                JOIN    
                    (
                        SELECT 
                            `entity_family_name`,
                            `number_given_name`,
                            `user_id`
                        FROM 
                            `address_book`
                        JOIN
                            `user` on `user`.email = `address_book`.`main_email`
                        WHERE `address_book`.type = "per"
                    ) 
                    `address_book` on `address_book`.user_id = `job_premium_service`.verified_by
            ) 
            `job_premium_service` ON `job_premium_service`.`address_book_id` = `address_book`.`address_book_id` ';

        $where = $this->filter($request, $columns, $bindings);

        //add our conditional parameter
        $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
        $where .= ' `address_book`.`type` = "per" ';

        if (isset($_POST['partner_id']) && $_POST['partner_id'] != '') {
            list($type,$partner_id)=explode('_',$_POST['partner_id']);
            if($type=='lp') {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book_connection`.`connection_id` = '{$partner_id}' AND `address_book_connection`.`connection_type` = 'lp'";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `partner_lep`.`connection_id` = '{$partner_id}' AND `partner_lep`.`connection_type` = 'lep'";
            }
        } else {
            if ($ent != false) {

                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book_connection`.`connection_id` = '{$ent}'";
            }
        }

        if (isset($_POST['country']) && $_POST['country'] != '') {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }


        if (isset($_POST['status']) && $_POST['status'] != '') {
            if ($_POST['status'] == 'request_ver') {
                //condition access from menu request verification
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "`personal_verification`.`status` IN ('request','process')";
            } else {
                $is_null = '';
                if ($_POST['status'] == 'unverified') {
                    $is_null .= ' OR (`personal_verification`.`status` IS NULL AND `address_book`.`type` = "per")';
                }
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "( `personal_verification`.`status`='{$_POST['status']}'  " . $is_null. ' )';
            }
        }

        if (isset($_POST['register_method']) && $_POST['register_method'] != '') {

            
            if ($_POST['register_method'] == -1) {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` > 0 ";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` = 0 ";
            }

        }

        $order .= (strpos(strtolower($order), 'order by') === false) ? ' ORDER BY ' : ', ';
        $order .= "`personal_verification`.`created_on` DESC";
        $select = "SELECT " . implode(", ", self::pluck($columns, 'db'));

        $qry1 = $select . "
             FROM `$table`
             $join
             $where
             $order
             $limit";
        //echo $qry1;
        $data = $this->db->query_array($qry1);

        $columns[] = array('db' => 'status', 'dt' => 'status');
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`
              $join
             $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];
        error_log($qry);
        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );
    }

    public function getAllRecruitmentOptimized($ent = false)
    {
        $request = $_POST;
        $table = 'address_book';

        $primaryKey = 'address_book.address_book_id';
        $columns = array(
            array('db' => 'address_book_per.title', 'dt' => 'title'),
            array('db' => 'address_book_per.middle_names', 'dt' => 'middle_names'),
            array('db' => 'address_book.number_given_name', 'dt' => 'number_given_name'),
            array('db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name'),
            array('db' => 'address_book.main_email', 'dt' => 'main_email'),
            array('db' => 'address_book.created_on', 'dt' => 'created_on'),
            array('db' => 'address_book_address.country', 'dt' => 'country'),
            array('db' => 'address_book_connection.connection_id', 'dt' => 'partner_id'),
            array('db' => 'address_book.created_by', 'dt' => 'created_by'),
            array('db' => 'partner_lep.connection_id', 'as' => 'partner_lep_id', 'dt' => 'partner_lep_id'),
            array('db' => 'partner.entity_family_name', 'as' => 'partner_name', 'dt' => 'partner_name'),
            array('db' => 'partner_lep_name.entity_family_name', 'as' => 'partner_lep_name', 'dt' => 'partner_lep_name'),

            array('db' => 'address_book.address_book_id', 'dt' => 'address_book_id'),
            array('db' => 'personal.status', 'dt' => 'status'),

            array('db' => 'job_application.job_application_id', 'dt' => 'job_application_id'),
            array('db' => 'job_application.job_speedy_code', 'dt' => 'job_speedy_code'),

            array('db' => 'job_speedy.job_speedy_category_id', 'dt' => 'job_speedy_category')

        );


        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);

        $join = 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`="lp"';
        $join .= 'LEFT JOIN `address_book_connection` as `partner_lep` ON `address_book`.`address_book_id` = `partner_lep`.`address_book_id` AND `partner_lep`.`connection_type`="lep"';
        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner_lep_name` ON `partner_lep`.`connection_id` = `partner_lep_name`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_per` ON `address_book`.`address_book_id` = `address_book_per`.`address_book_id` ';
        $join .= 'LEFT JOIN `personal` ON `personal`.`address_book_id` = `address_book`.`address_book_id`';
        $join .= 'LEFT JOIN `job_application` ON `address_book`.`address_book_id` = `job_application`.`address_book_id`';
        $join .= 'LEFT JOIN `job_speedy` ON `job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`';

        $where = $this->filter($request, $columns, $bindings);

        //add our conditional parameter
        $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
        $where .= ' `address_book`.`type` = "per" ';

        if (isset($_POST['partner_id']) && $_POST['partner_id'] != '') {
            list($type,$partner_id)=explode('_',$_POST['partner_id']);
            if($type=='lp') {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book_connection`.`connection_id` = '{$partner_id}' AND `address_book_connection`.`connection_type` = 'lp'";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `partner_lep`.`connection_id` = '{$partner_id}' AND `partner_lep`.`connection_type` = 'lep'";
            }
        } else {
            if ($ent != false) {

                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book_connection`.`connection_id` = '{$ent}'";
            }
        }

        if (isset($_POST['country']) && $_POST['country'] != '') {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }


        if (isset($_POST['status']) && $_POST['status'] != '') {
            if ($_POST['status'] == 'request_ver') {
                //condition access from menu request verification
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "`personal`.`status` IN ('request','process')";
            } else {
                $is_null = '';
                if ($_POST['status'] == 'unverified') {
                    $is_null .= ' OR (`personal`.`status` IS NULL AND `address_book`.`type` = "per")';
                }
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "( `personal`.`status`='{$_POST['status']}'  " . $is_null. ' )';
            }
        }

        if (isset($_POST['register_method']) && $_POST['register_method'] != '') {

            
            if ($_POST['register_method'] == -1) {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` > 0 ";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` = 0 ";
            }

        }

        if (isset($_POST['job_category']) && $_POST['job_category'] != '') {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `job_speedy`.`job_speedy_category_id` = {$_POST['job_category']}";
        }

        $select = "SELECT " . implode(", ", self::pluck($columns, 'db'));

        $qry1 = $select . "
             FROM `$table`
             $join
             $where
             $order
             $limit";
        $data = $this->db->query_array($qry1);

        $columns[] = array('db' => 'status', 'dt' => 'status');
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`
              $join
             $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM `$table` $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );
    }

    public function getAllRecruitmentExport2($country,$status,$partner,$job_category,$ent=false) {
        $out = array();
        $sql = '
        SELECT 
            address_book_per.title, address_book_per.middle_names, address_book.number_given_name, address_book.entity_family_name, address_book.main_email, address_book.created_on, address_book_address.country, address_book_connection.connection_id, partner_lep.connection_id as partner_lep_id, partner.entity_family_name as partner_name, partner_lep_name.entity_family_name as partner_lep_name, address_book.address_book_id, personal_verification.status, personal_verification.verification_info, job_application.job_application_id, job_application.job_speedy_code, job_speedy.job_speedy_category_id
        FROM `address_book`
        LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`="lp" 
        LEFT JOIN `address_book_connection` as `partner_lep` ON `address_book`.`address_book_id` = `partner_lep`.`address_book_id` AND `partner_lep`.`connection_type`="lep"
        LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"
        LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` 
        LEFT JOIN `address_book` as `partner_lep_name` ON `partner_lep`.`connection_id` = `partner_lep_name`.`address_book_id` 
        LEFT JOIN `address_book_per` ON `address_book`.`address_book_id` = `address_book_per`.`address_book_id` 
        LEFT JOIN `personal_verification` ON `personal_verification`.`id` = (
            SELECT
                    `id`
            FROM 
                `personal_verification`
            WHERE
                    `address_book_id` = `address_book`.address_book_id
            ORDER BY modified_on DESC
            LIMIT 1
            )
        LEFT JOIN `job_application` ON `address_book`.`address_book_id` = `job_application`.`address_book_id`
        LEFT JOIN `job_speedy` ON `job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`
         WHERE  `address_book`.`type` = "per"';
        $where='';
        if (isset($partner) && $partner != '') {
            list($type,$partner_id)=explode('_',$partner);
            if($type=='lp') {
                $where .= " AND (`address_book_connection`.`connection_id` = '{$partner_id}' AND `address_book_connection`.`connection_type` = 'lp')";
            } else {
                $where .= " AND (`partner_lep`.`connection_id` = '{$partner_id}' AND `partner_lep`.`connection_type` = 'lep')";
            }
        } else {
            if ($ent != false) {
                $where .= " AND `address_book_connection`.`connection_id` = '{$ent}'";
            }
        }

        if (isset($country) && $country != '') {
            $where .= " AND `address_book_address`.`country` = '{$country}' ";
        }


        if (isset($status) && $status != '') {
            if ($status == 'request_ver') {
                //condition access from menu request verification
                $where .= " AND `personal_verification`.`status` IN ('request','process')";
            } else {
                $is_null = '';
                if ($status == 'unverified') {
                    $is_null .= ' OR (`personal_verification`.`status` IS NULL AND `address_book`.`type` = "per")';
                }

                $where .= " AND ( `personal_verification`.`status`='{$status}'  " . $is_null. ' )';
            }
        }
        if (!empty($job_category)) {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `job_speedy`.`job_speedy_category_id` = {$job_category}";
        }
        $order = "ORDER BY `address_book`.`created_on` DESC";

        $stmt = $this->db->prepare($sql.$where.$order);
        $stmt->bind_result($title, $middle_names, $number_given_name, $entity_family_name, $main_email,$created_on,$country,$connection_id,$partner_lep_id,$partner_name,$partner_lep_name,$address_book_id,$status,$verification_info);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'title' => $title,
                'middle_names' => $middle_names,
                'number_given_name' => $number_given_name,
                'entity_family_name' => $entity_family_name,
                'main_email' => $main_email,
                'created_on' => $created_on,
                'country' => $country,
                'connection_id' => $connection_id,
                'partner_lep_id' => $partner_lep_id,
                'partner_name' => $partner_name,
                'partner_lep_name' => $partner_lep_name,
                'address_book_id' => $address_book_id,
                'status' => $status,
                'verification_info' => $verification_info
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function getAllRecruitmentExport($country,$status,$partner,$job_category,$ent=false) {
        $out = array();
        $sql = '
        SELECT 
            address_book_per.title, address_book_per.middle_names, address_book.number_given_name, address_book.entity_family_name, address_book.main_email, address_book.created_on, address_book_address.country, address_book_connection.connection_id, partner_lep.connection_id as partner_lep_id, partner.entity_family_name as partner_name, partner_lep_name.entity_family_name as partner_lep_name, address_book.address_book_id, personal_verification.status, personal_verification.verification_info, job_speedy_category.name, job_speedy.job_speedy_category_id
        FROM `address_book`
        LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` AND `address_book_connection`.`connection_type`="lp" 
        LEFT JOIN `address_book_connection` as `partner_lep` ON `address_book`.`address_book_id` = `partner_lep`.`address_book_id` AND `partner_lep`.`connection_type`="lep"
        LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"
        LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` 
        LEFT JOIN `address_book` as `partner_lep_name` ON `partner_lep`.`connection_id` = `partner_lep_name`.`address_book_id` 
        LEFT JOIN `address_book_per` ON `address_book`.`address_book_id` = `address_book_per`.`address_book_id` 
        LEFT JOIN `personal_verification` ON `personal_verification`.`id` = (
            SELECT
                    `id`
            FROM 
                `personal_verification`
            WHERE
                    `address_book_id` = `address_book`.address_book_id
            ORDER BY modified_on DESC
            LIMIT 1
            )
        LEFT JOIN `job_application` ON `address_book`.`address_book_id` = `job_application`.`address_book_id`
        LEFT JOIN `job_speedy` ON `job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`
        LEFT JOIN `job_speedy_category` ON `job_speedy`.`job_speedy_category_id` = `job_speedy_category`.`job_speedy_category_id`
         WHERE  `address_book`.`type` = "per"
        ';
        $where='';
        if (isset($partner) && $partner != '') {
            list($type,$partner_id)=explode('_',$partner);
            if($type=='lp') {
                $where .= " AND (`address_book_connection`.`connection_id` = '{$partner_id}' AND `address_book_connection`.`connection_type` = 'lp')";
            } else {
                $where .= " AND (`partner_lep`.`connection_id` = '{$partner_id}' AND `partner_lep`.`connection_type` = 'lep')";
            }
        } else {
            if ($ent != false) {
                $where .= " AND `address_book_connection`.`connection_id` = '{$ent}'";
            }
        }

        if (isset($country) && $country != '') {
            $where .= " AND `address_book_address`.`country` = '{$country}' ";
        }


        if (isset($status) && $status != '') {
            if ($status == 'request_ver') {
                //condition access from menu request verification
                $where .= " AND `personal_verification`.`status` IN ('request','process')";
            } else {
                $is_null = '';
                if ($status == 'unverified') {
                    $is_null .= ' OR (`personal_verification`.`status` IS NULL AND `address_book`.`type` = "per")';
                }

                $where .= " AND ( `personal_verification`.`status`='{$status}'  " . $is_null. ' )';
            }
        }
        if (isset($job_category) && !empty($job_category)) {
            $where .= " AND `job_speedy`.`job_speedy_category_id` = {$job_category} ";
        }
        $order = "ORDER BY `address_book`.`created_on` DESC";

        $stmt = $this->db->prepare($sql.$where.$order);
        $stmt->bind_result($title, $middle_names, $number_given_name, $entity_family_name, $main_email,$created_on,$country,$connection_id,$partner_lep_id,$partner_name,$partner_lep_name,$address_book_id,$status,$verification_info,$job_category,$job_speedy_category_id);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
            $out[] = array(
                'title' => $title,
                'middle_names' => $middle_names,
                'number_given_name' => $number_given_name,
                'entity_family_name' => $entity_family_name,
                'main_email' => $main_email,
                'created_on' => $created_on,
                'country' => $country,
                'connection_id' => $connection_id,
                'partner_lep_id' => $partner_lep_id,
                'partner_name' => $partner_name,
                'partner_lep_name' => $partner_lep_name,
                'address_book_id' => $address_book_id,
                'status' => $status,
                'verification_info' => $verification_info,
                'job_category' => $job_category,
                'job_speedy_category_id' => $job_speedy_category_id
            );
        }
        $stmt->free_result();
        $stmt->close();
        return $out;
    }

    public function getAllRecruitmentSelect()
    {
        $out = [];
        
        $select = "
                    SELECT 
                        `address_book`.`address_book_id`,
                        `address_book`.`main_email`,
                        `address_book_address`.`country`,
                        `address_book`.`entity_family_name`,
                        `address_book`.`number_given_name`,
                        `personal`.`status`,
                        `job_speedy`.`job_speedy_category_id`
                    FROM 
                        `address_book` ";
        $where = 'WHERE  `address_book`.`type` = "per"';
        $join = '';

        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"';
        $join .= 'LEFT JOIN `personal` ON `personal`.`address_book_id` = `address_book`.`address_book_id`';
        $join .= 'LEFT JOIN `job_application` ON `address_book`.`address_book_id` = `job_application`.`address_book_id`';
        $join .= 'LEFT JOIN `job_speedy` ON `job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`';
        
        if (isset($_POST['country']) && $_POST['country'] != '') {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }

        if (isset($_POST['status']) && $_POST['status'] != '') {
            if ($_POST['status'] == 'request_ver') {
                //condition access from menu request verification
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "`personal`.`status` IN ('request','process')";
            } else {
                $is_null = '';
                if ($_POST['status'] == 'unverified') {
                    $is_null .= ' OR (`personal`.`status` IS NULL AND `address_book`.`type` = "per")';
                }
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= "( `personal`.`status`='{$_POST['status']}'  " . $is_null. ' )';
            }
        }

        if (isset($_POST['job_category']) && $_POST['job_category'] != '') {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `job_speedy`.`job_speedy_category_id` = {$_POST['job_category']}";
        }

        $sql = $select.$join.$where;

        $stmt = $this->db->query($sql);

        while ($result = $stmt->fetch_assoc()) {
            $out[] = $result;
        }

        return $out;
    }

    public function getUnverifiedRecruitment()
    {
        $request = $_POST;
        $table = 'address_book';

        $primaryKey = 'address_book.address_book_id';
        $columns = array(
            array('db' => 'address_book.number_given_name', 'dt' => 'entity_family_name'),
            array('db' => 'address_book.main_email', 'dt' => 'main_email'),
            array('db' => 'address_book_address.country', 'dt' => 'country'),
            array('db' => 'address_book_connection.connection_id', 'dt' => 'partner_id'),
            array('db' => 'partner.entity_family_name', 'dt' => 'partner_name'),

            array('db' => 'address_book.address_book_id', 'dt' => 'address_book_id'),
            array('db' => 'personal_verification.status', 'dt' => 'status'),
        );


        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);

        $join = 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN `personal_verification` ON `personal_verification`.`id` = (
            SELECT 
                `id`
            FROM
                `personal_verification`
            WHERE
                `address_book_id` = `address_book`.address_book_id
            ORDER BY modified_on DESC
            LIMIT 1
         )';

        $where = $this->filter($request, $columns, $bindings);

        //add our conditional parameter
        $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
        $where .= ' `personal_verification`.`status` = "request" ';

        $select = "SELECT " . implode(", ", self::pluck($columns, 'db'));

        $qry1 = $select . "
             FROM `$table`
             $join
             $where
             $order
             $limit";
        $data = $this->db->query_array($qry1);

        $columns[] = array('db' => 'status', 'dt' => 'status');
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`
              $join
             $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );
    }

    // get all list partner from table
    public function getListPartner($type='LP')
    {
        $out = array();

        $this->db->set_charset('utf8');
        $sql = "SELECT
            `address_book`.`address_book_id`,
            `address_book`.`entity_family_name`,
            `address_book`.`number_given_name`
          FROM 
            `partner`
        LEFT JOIN 
            `partner_type` on `partner_type`.`address_book_id` = `partner`.`address_book_id`
        LEFT JOIN 
            `address_book` on `address_book`.`address_book_id` = `partner`.`address_book_id`
        ";
        if($type=='LP') {
            $sql .= " WHERE `partner_type`.`partner_type`='LP'";
        } else {
            $sql .= " WHERE `partner_type`.`partner_type`='LEP'";
        }
        $stmt = $this->db->prepare($sql);

        $stmt->bind_result($address_book_id, $entity_family_name, $number_given_name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $out[$address_book_id] = array(
                'id' => $address_book_id,
                'name' => $entity_family_name
            );

        }
        $stmt->close();
        return $out;
    }

    public function updatePartner($address_book_id, $partner_id,$type='lp')
    {
        //make absolutly certain the person is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] < 0) {
            $msg = 'Security error: you can not update user information.';
            throw new \RuntimeException($msg);
        }
        $out = false;

        $qry = "INSERT INTO
                    `address_book_connection`
                SET
                    `address_book_id` = '{$address_book_id}',
                    `connection_type` = '{$type}',
                    `connection_id` = '{$partner_id}',
                    `created_on` = CURRENT_TIMESTAMP,
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ON DUPLICATE KEY UPDATE
                    `connection_id` = '{$partner_id}',
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ";
        $this->db->query($qry);

        if ($this->db->affected_rows() != -1) {
            $out = true;
        }

        return $out;
    }

    public function deletePartner($address_book_id,$type)
    {
        //make absolutly certain the person is logged in
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] < 0) {
            $msg = 'Security error: you can not update user information.';
            throw new \RuntimeException($msg);
        }
        $out = false;

        $qry = "DELETE FROM
                    `address_book_connection`
                WHERE
                    `address_book_id` = '{$address_book_id}'
                AND
                    `connection_type` = '{$type}'
                ";
        $this->db->query($qry);

        if ($this->db->affected_rows() != -1) {
            $out = true;
        }

        return $out;
    }


    public function getJobApplicationInterviewAnswer($job_application_id, $type = 'prescreen')
    {
        $out = array();

        $sql = "SELECT
                    `job_interview_answer`.`type`,
                    `job_interview_answer`.`question_id`,
                    `job_interview_answer`.`answer`,
                    `job_interview_answer_text`.`text`,
                    `job_interview_answer`.`created_on`,
                    `job_interview_answer`.`created_by`,
                    `job_interview_answer`.`modified_on`,
                    `job_interview_answer`.`modified_by`
                FROM 
                    `job_interview_answer`
                LEFT JOIN
                    `job_interview_answer_text` on `job_interview_answer_text`.`question_id` = `job_interview_answer`.`question_id`
                    AND
                    `job_interview_answer_text`.`job_application_id` = `job_interview_answer`.`job_application_id`
                WHERE
                    `job_interview_answer`.`job_application_id` = ?
                AND 
                    `job_interview_answer`.`type` = ?
            UNION

                SELECT
                    `job_interview_answer_text`.`type`,
                    `job_interview_answer_text`.`question_id`,
                    `job_interview_answer`.`answer`,
                    `job_interview_answer_text`.`text`,
                    `job_interview_answer_text`.`created_on`,
                    `job_interview_answer_text`.`created_by`,
                    `job_interview_answer_text`.`modified_on`,
                    `job_interview_answer_text`.`modified_by`
                FROM 
                    `job_interview_answer_text`
                LEFT JOIN
                    `job_interview_answer` on `job_interview_answer`.`question_id` = `job_interview_answer_text`.`question_id`
                    AND
                    `job_interview_answer`.`job_application_id` = `job_interview_answer_text`.`job_application_id`
                WHERE
                    `job_interview_answer_text`.`job_application_id` = ?
                AND 
                    `job_interview_answer_text`.`type` = ?
                AND 
                    `job_interview_answer`.`answer` IS NULL
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isis', $job_application_id, $type, $job_application_id, $type);
        $stmt->bind_result($type, $question_id, $answer, $answer_text, $created_on, $created_by, $modified_on, $modified_by);
        $stmt->execute();
        while ($stmt->fetch()) {
            $out[$question_id] = array(
                'type' => $type,
                // 'question_id' => $question_id,
                'answer' => $answer,
                'text' => $answer_text,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by
            );
        }
        $stmt->close();

        return $out;
    }

    public function getJobApplicationInterviewData($job_application_id, $type = 'prescreen')
    {
        $out = array();

        $sql = "SELECT
                    `job_interview_answer`.`type`,
                    `job_interview_answer`.`created_on`,
                    `job_interview_answer`.`created_by`,
                    `job_interview_answer`.`modified_on`,
                    `job_interview_answer`.`modified_by`,
                    `address_book`.`number_given_name`,
                    `address_book`.`entity_family_name`
                FROM 
                    `job_interview_answer`
                LEFT JOIN 
                    (
                    SELECT 
                        `user_id`,`number_given_name`,`entity_family_name` 
                    FROM 
                        `address_book` 
                    JOIN 
                        `user` on `user`.`email` = `address_book`.`main_email` 
                    WHERE 
                        `address_book`.`type` = 'per'
                    ) as `address_book` on `address_book`.`user_id` = `job_interview_answer`.`created_by`
                WHERE
                    `job_interview_answer`.`job_application_id` = ?
                AND 
                    `job_interview_answer`.`type` = ?
                LIMIT 1
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('is', $job_application_id, $type);
        $stmt->bind_result($type, $created_on, $created_by, $modified_on, $modified_by, $number_given_name, $entity_family_name);
        $stmt->execute();
        if ($stmt->fetch()) {
            $out = array(
                'type' => $type,
                'created_on' => date('d M Y', strtotime($created_on)),
                'created_by' => $created_by,
                'modified_on' => $modified_on,
                'modified_by' => $modified_by,
                'created_by_full_name' => $number_given_name . ' ' . $entity_family_name
            );
        }
        $stmt->close();

        return $out;
    }

    public function insertJobApplicationInterviewAnswer($data, $type = 'prescreen')
    {
        $sql = "INSERT INTO 
                    `job_interview_answer`
                SET
                    `job_application_id` = ?,
                    `type` = ?,
                    `question_id` = ?,
                    `answer` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ON DUPLICATE KEY UPDATE
                    `answer` = ?,
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isiss",
            $data['job_application_id'],
            $type,
            $data['question_id'],
            $data['answer'],
            $data['answer']
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function insertJobApplicationInterviewAnswerText($data, $type = 'prescreen')
    {
        $sql = "INSERT INTO 
                    `job_interview_answer_text`
                SET
                    `job_application_id` = ?,
                    `type` = ?,
                    `question_id` = ?,
                    `text` = ?,
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id},
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
                ON DUPLICATE KEY UPDATE
                    `text` = ?,
                    `modified_on`= CURRENT_TIMESTAMP, 
                    `modified_by`= {$this->user_id}
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isiss",
            $data['job_application_id'],
            $type,
            $data['question_id'],
            $data['text'],
            $data['text']
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function deleteJobApplicationInterviewAnswerText($question_id,$job_application_id,$type='prescreen')
    {
        $sql = "DELETE
                    FROM `job_interview_answer_text`
                WHERE
                    `question_id` = ?
                AND
                    `type` = ?
                AND
                    `job_application_id` = ?
                ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isi', $question_id,$type,$job_application_id);
        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function getJobPrescreenDatatable($ent = false)
    {
        $this->generic_obj = \core\app\classes\generic\generic::getInstance();
        $request = $_POST;
        $table = 'job_prescreen';

        $primaryKey = 'job_prescreen.job_application_id';
        $columns = array(
            array('db' => 'job_prescreen.job_application_id', 'dt' => 'job_application_id'),
            array('db' => 'job_prescreen.status', 'dt' => 'status'),
            array('db' => 'job_prescreen.sending_on', 'dt' => 'sending_on'),
            array('db' => 'job_prescreen.accepted_on', 'dt' => 'accepted_on'),
            array('db' => 'job_prescreen.revision_on', 'dt' => 'revision_on'),
            array('db' => 'address_book_per.title', 'dt' => 'title'),
            array('db' => 'address_book.number_given_name', 'dt' => 'number_given_name', 'formatter' => function ($d, $row) {
                return 'Mr ' . $this->generic_obj->getName('per', $row['entity_family_name'], $row['number_given_name'], ADDRESS_BOOK_OUTPUT_PER_NAME, ADDRESS_BOOK_OUTPUT_ENT_NAME);
            }),
            array('db' => 'address_book.entity_family_name', 'dt' => 'entity_family_name'),
            array('db' => 'address_book_per.middle_names', 'dt' => 'middle_names'),
            array('db' => 'address_book.main_email', 'dt' => 'main_email'),
            array('db' => 'address_book.address_book_id', 'dt' => 'address_book_id'),
            array('db' => 'address_book.created_by', 'dt' => 'created_by'),
            array('db' => 'address_book_address.country', 'dt' => 'country'),
            array('db' => 'address_book_connection.connection_id', 'dt' => 'partner_id'),
            array('db' => 'partner.entity_family_name', 'as' => 'partner_name', 'dt' => 'partner_name'),
            array('db' => 'job_application.status', 'as' => 'job_application_status', 'dt' => 'job_application_status')
        );


        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);

        $join = 'LEFT JOIN `job_application` ON `job_application`.`job_application_id` = `job_prescreen`.`job_application_id` ';
        $join .= 'LEFT JOIN `address_book` ON `address_book`.`address_book_id` = `job_application`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_connection` ON `address_book`.`address_book_id` = `address_book_connection`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_address` ON `address_book`.`address_book_id` = `address_book_address`.`address_book_id` AND `address_book_address`.`type` = "main"';
        $join .= 'LEFT JOIN `address_book` as `partner` ON `address_book_connection`.`connection_id` = `partner`.`address_book_id` ';
        $join .= 'LEFT JOIN `address_book_per` ON `address_book`.`address_book_id` = `address_book_per`.`address_book_id` ';

        $where = $this->filter($request, $columns, $bindings);

        //add our conditional parameter
        $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
        $where .= ' `address_book`.`type` = "per" ';
        $where .= ' AND `job_application`.`status` != "interview" ';

        if ($ent != false) {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        if (isset($_POST['partner_id']) && $_POST['partner_id'] != '') {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$_POST['partner_id']}' ";
        }

        if (isset($_POST['country']) && $_POST['country'] != '') {

            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }

        //add our conditional parameter
        $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
        $where .=' `address_book`.`type` = "per" ';
        $where .=' AND `job_application`.`status` NOT IN ("interview", "hired", "not_hired", "allocated") ';

        if($ent != false){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$ent}' ";
        }

        if(isset($_POST['partner_id']) && $_POST['partner_id'] != ''){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_connection`.`connection_id` = '{$_POST['partner_id']}' ";
        }

        if(isset($_POST['country']) && $_POST['country'] != ''){

            $where .= (strpos(strtolower($where),'where') === false)? ' WHERE ' :  ' AND ';
            $where .= " `address_book_address`.`country` = '{$_POST['country']}' ";
        }

        if (isset($_POST['status']) && $_POST['status'] != '') {
            $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
            $where .= " `job_prescreen`.`status`='{$_POST['status']}'";
        }

        if (isset($_POST['register_method']) && $_POST['register_method'] != '') {

            
            if ($_POST['register_method'] == -1) {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` > 0 ";
            } else {
                $where .= (strpos(strtolower($where), 'where') === false) ? ' WHERE ' : ' AND ';
                $where .= " `address_book`.`created_by` = 0 ";
            }

        }

        $select = "SELECT " . implode(", ", self::pluck($columns, 'db'));

        $qry1 = $select . "
             FROM `$table`
             $join
             $where
             $order
             $limit";
        $data = $this->db->query_array($qry1);

        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`
              $join
             $where";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`  $join $where";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );
    }

    public function getJobPrescreen($job_application_id)
    {
        $out = null;

        $sql = "SELECT
                    `job_prescreen`.`job_application_id`,
                    `job_prescreen`.`status`,
                    `job_prescreen`.`sending_on`,
                    `job_prescreen`.`sending_by`,
                    `job_prescreen`.`accepted_on`,
                    `job_prescreen`.`revision_on`,
                    `job_prescreen`.`created_on`,
                    `job_prescreen`.`created_by`,
                    `job_prescreen`.`hash`
                FROM 
                    `job_prescreen`
                WHERE
                    `job_prescreen`.`job_application_id` = ?
                LIMIT 1
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $job_application_id);
        $stmt->bind_result($job_application_id, $status, $sending_on, $sending_by, $accepted_on, $revision_on, $created_on, $created_by, $hash);
        $stmt->execute();
        if ($stmt->fetch()) {
            $out = array(
                'job_application_id' => $job_application_id,
                'status' => $status,
                'sending_on' => $sending_on,
                'sending_by' => $sending_by,
                'accepted_on' => $accepted_on,
                'revision_on' => $revision_on,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'hash' => $hash,
            );
        }
        $stmt->close();

        return $out;
    }

    public function getJobPrescreenByHash($hash)
    {
        $out = null;

        $sql = "SELECT
                    `job_prescreen`.`job_application_id`,
                    `job_prescreen`.`status`,
                    `job_prescreen`.`sending_on`,
                    `job_prescreen`.`sending_by`,
                    `job_prescreen`.`accepted_on`,
                    `job_prescreen`.`revision_on`,
                    `job_prescreen`.`created_on`,
                    `job_prescreen`.`created_by`,
                    `job_prescreen`.`hash`
                FROM 
                    `job_prescreen`
                WHERE
                    `job_prescreen`.`hash` = ?
                LIMIT 1
            ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $hash);
        $stmt->bind_result($job_application_id, $status, $sending_on, $sending_by, $accepted_on, $revision_on, $created_on, $created_by, $hash);
        $stmt->execute();
        if ($stmt->fetch()) {
            $out = array(
                'job_application_id' => $job_application_id,
                'status' => $status,
                'sending_on' => $sending_on,
                'sending_by' => $sending_by,
                'accepted_on' => $accepted_on,
                'revision_on' => $revision_on,
                'created_on' => $created_on,
                'created_by' => $created_by,
                'hash' => $hash,
            );
        }
        $stmt->close();

        return $out;
    }

    public function insertJobPrescreen($data)
    {
        $sql = "INSERT INTO 
                    `job_prescreen`
                SET
                    `job_prescreen`.`job_application_id` = ?,
                    `job_prescreen`.`hash` = ?,
                    `job_prescreen`.`status` = 'pending',
                    `created_on`= CURRENT_TIMESTAMP, 
                    `created_by`= {$this->user_id}
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is",
            $data['job_application_id'], $data['hash']
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function sendingJobPrescreen($job_application_id)
    {
        $sql = "UPDATE 
                    `job_prescreen`
                SET
                    `job_prescreen`.`status` = 'sending',
                    `job_prescreen`.`sending_on` = CURRENT_TIMESTAMP,
                    `job_prescreen`.`sending_by` = {$this->user_id}
                WHERE
                  `job_prescreen`.`job_application_id` = ?
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",
            $job_application_id
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function acceptJobPrescreen($job_application_id)
    {
        $sql = "UPDATE 
                    `job_prescreen`
                SET
                    `job_prescreen`.`status` = 'accepted',
                    `job_prescreen`.`accepted_on` = CURRENT_TIMESTAMP
                WHERE
                  `job_prescreen`.`job_application_id` = ?
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",
            $job_application_id
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function revisionJobPrescreen($job_application_id)
    {
        $sql = "UPDATE 
                    `job_prescreen`
                SET
                    `job_prescreen`.`status` = 'revision',
                    `job_prescreen`.`revision_on` = CURRENT_TIMESTAMP
                WHERE
                  `job_prescreen`.`job_application_id` = ?
            ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i",
            $job_application_id
        );

        $stmt->execute();
        $out = ($stmt->affected_rows == 1) ? true : false;
        $stmt->close();

        return $out;
    }

    public function checkPersonalDataUser($address_book_id,$table,$type=false)
    {
        $out = [];

        $where="";
        if($type) {
            $where =" and type='{$type}'";
        }
        $sql = "SELECT 
                    *
                FROM 
                    ".$table."
                WHERE
                    `address_book_id` = {$address_book_id}
                    ".$where."
            ";
        $out= $this->db->query_array($sql);
        return $out;
    }

    public function getCheckRecruitment($day,$ent=false) {
        $out = [];

        $where = " where `address_book`.`type`='per'";
        $where .= " and `address_book`.`created_on`< date_sub(curdate(),interval ".$day." day)";
        $where .= " and `address_book`.`address_book_id` not in (select `personal_verification`.`address_book_id` from `personal_verification`)";
        if($ent){
            $join = " left join `address_book_connection` on `address_book`.`address_book_id`=`address_book_connection`.`address_book_id`";
            $where .= " and `address_book_connection`.`connection_id`={$ent}";
        }else{
            $join = ' ';
        }
        $sql = "SELECT `address_book`.`address_book_id`,`address_book`.`created_on`
                FROM 
                `address_book`
                ".$join.$where."
            ";
        // echo "sql : ".$sql;
        $out= $this->db->query_array($sql);
        return $out;
    }
}