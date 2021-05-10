<?php
namespace core\modules\job\models\common;

/**
 * Final job/demand_db class.
 *
 * @final
 * 
 */
final class demand_db extends \core\app\classes\module_base\module_db {

    public function __construct()
    {
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
        return;
    }

    /**
     * getTotalCurrentDemandfromJobMasterDemand function.
     *
     * Gets the total CURRENT demand for ALL positions from the job master demand table which MIGHT be inconsistent with the
     * Speedy jobs available if the job_demand_master has demand for a job in the job_master table that is NOT linked to a Speedy job.
     *
     * * use getJobMasterIdArrayWithDemandWithoutJobSpeedyCode() to get a list of items with demand but no speedy code
     * * if everything is connected then this total should be the same as the total when everything is "added up" in the array generated
     * * by getJobSpeedyDemandArray()
     *
     * @access public
     * @return int
     */
    public function getTotalCurrentDemandfromJobMasterDemand()
    {
        $current_demand = 0;

        $sql = "SELECT
						`job_demand_master`.`demand`,
        				(
        					SELECT 
        						COUNT(`job_demand_allocation`.`job_demand_master_id`)
        					FROM
        						`job_demand_allocation`
        					WHERE
        						`job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id`
        					AND
		        				`job_demand_allocation`.`status` IN 
		        				(
									'security',
									'review',
									'principal',
									'candidate',
									'pdf',
									'complete'
								)
        				) as `allocated`
        		FROM 
        				`job_demand_master`
        		LEFT JOIN
        				`job_demand_allocation`
        		ON
        				`job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`
        		WHERE 
        				`job_demand_master`.`expiry_on` >= NOW()
        		GROUP BY
        				`job_demand_master`.`job_demand_master_id`		
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($demand,$allocation);
        $stmt->execute();
        while($stmt->fetch())
        {
	        $current_demand += $demand;
	        $current_demand -= $allocation;
        };
        $stmt->close();

        return $current_demand;
    }

	/**
	 * getCurrentDemandForJobMasterId function.
	 *
	 * Gets the current demand based on a specific job master id NOT the speedy code
	 *
	 * @access public
	 * @param int $job_master_id
	 * @return int
	 */
	public function getCurrentDemandForJobMasterId($job_master_id)
    {
        $current_demand = 0;

		$sql = "SELECT
						`job_demand_master`.`demand`,
        				(
        					SELECT 
        						COUNT(`job_demand_allocation`.`job_demand_master_id`)
        					FROM
        						`job_demand_allocation`
        					WHERE
        						`job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id`
        					AND
		        				`job_demand_allocation`.`status` IN 
		        				(
									'security',
									'review',
									'principal',
									'candidate',
									'pdf',
									'complete'
								)
        				) as `allocated`
        		FROM 
        				`job_demand_master`
        		LEFT JOIN
        				`job_demand_allocation`
        		ON
        				`job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`
        		WHERE 
        				`job_demand_master`.`job_master_id` = ? 
        		AND
        				`job_demand_master`.`expiry_on` >= NOW()
        		GROUP BY
        				`job_demand_master`.`job_demand_master_id`
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_master_id);
        $stmt->bind_result($demand,$allocation);
        $stmt->execute();
        while($stmt->fetch())
        {
	        $current_demand += $demand;
	        $current_demand -= $allocation;
        };
        $stmt->close();

        return $current_demand;
    }

    /**
     * getJobMasterIdArrayForJobSpeedyCode function.
     *
     * Returns an array of the job master id's that are associated with a particular job speedy codes
     *
     * @access public
     * @param str $job_speedy_code
     * @return arr
     */
    public function getJobMasterIdArrayForJobSpeedyCode($job_speedy_code)
    {
	    $job_master_id_array = array();

            $sql = "SELECT 
                            `job_master_id`,
                            `principal_code`,
                            `brand_code`,
                            `job_code`,
                            `job_title`
                    FROM 
                            `job_master`
                    WHERE 
                            `job_speedy_code` = ?		
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_speedy_code);
        $stmt->bind_result($job_master_id,$principal_code,$brand_code,$job_code,$job_title);
        $stmt->execute();
        while($stmt->fetch())
        {
	        $job_master_id_array[$job_master_id] = array(
	        	'principal_code' => $principal_code,
	        	'brand_code' => $brand_code,
	        	'job_code' => $job_code,
	        	'job_title' => $job_title
	        );
        }
        $stmt->close();

        return $job_master_id_array;
    }

    /**
     * getJobSpeedyCodeForJobMasterId function.
     *
     * Get the job speedy code based from a job master id and return null if one does not exist
     *
     * @access public
     * @param int $job_master_id
     * @return str
     */
    public function getJobSpeedyCodeForJobMasterId($job_master_id)
    {
        $job_speedy_code = null;

		$sql = "SELECT 
        				`job_speedy_code`
        		FROM 
        				`job_master`
        		WHERE 
        				`job_master_id` = ?		
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_master_id);
        $stmt->bind_result($job_speedy_code);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();

        return $job_speedy_code;
    }

	/**
	 * getCurrentDemandForJobSpeedyCode function.
	 *
	 * Gets the CURRENT demand for a job speedy code.
	 *
	 * @access public
	 * @param str $job_speedy_code
	 * @return int
	 */
	public function getCurrentDemandForJobSpeedyCode($job_speedy_code)
    {
	    $current_demand = 0;

        $sql = "SELECT
        				`demand`,
        				(
        					SELECT 
        						COUNT(`job_demand_allocation`.`job_demand_master_id`)
        					FROM
        						`job_demand_allocation`
        					WHERE
        						`job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id`
        					AND
		        				`job_demand_allocation`.`status` IN 
		        				(
									'security',
									'review',
									'principal',
									'candidate',
									'pdf',
									'complete'
								)
        				) as `allocated`
        						
        		FROM 
        				`job_demand_master`
        		WHERE 
        				`job_master_id` IN (
        				
	        				SELECT 
			        				`job_master_id`
			        		FROM 
			        				`job_master`
			        		WHERE 
			        				`job_speedy_code` = ? 
						)
        		AND
        				`expiry_on` >= NOW()
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$job_speedy_code);
        $stmt->bind_result($demand,$allocation);
        $stmt->execute();
        while($stmt->fetch())
        {
	        $current_demand += $demand;
	        $current_demand -= $allocation;
        };
        $stmt->close();

        return $current_demand;
    }

    /**
     * getJobMasterIdArrayWithDemandWithoutJobSpeedyCode function.
     *
     * Used to find if we have a demand for a job master id that does not have a job speedy code associate with it.
     *
     * Should be used to make sure we never have a demand for something that a person can not apply for. So it should be run
     * after every demand change to the job_demand_master table
     *
     * @access public
     * @return array
     */
    public function getJobMasterIdArrayWithDemandWithoutJobSpeedyCode()
    {
	    $unlinked_demand_array = array();

        $sql = "SELECT
        				`job_master`.`job_master_id`,
        				`job_master`.`principal_code`,
        				`job_master`.`brand_code`,
        				`job_master`.`job_code`,
        				`job_master`.`job_title`,
        				SUM(`job_demand_master`.`demand`) as 'demand'
        		FROM 
        				`job_master`
        		LEFT JOIN
						`job_demand_master`
				ON
						`job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
        		WHERE 
        				`job_master`.`job_speedy_code` NOT IN (
	        			
	        				SELECT 
			        				`job_speedy_code`
			        		FROM 
			        				`job_speedy`
						)
        		AND		
        				`job_demand_master`.`expiry_on` >= NOW()
                GROUP BY 
                		`job_master`.`job_master_id`
                ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_master_id,$principal_code,$brand_code,$job_code,$job_title,$demand);
        $stmt->execute();
        while($stmt->fetch())
        {
	        $unlinked_demand_array[$job_master_id] = array(
	        	'principal_code' => $principal_code,
	        	'brand_code' => $brand_code,
	        	'job_code' => $job_code,
	        	'job_title' => $job_title,
	        	'demand' => $demand
	        );
        }
        $stmt->close();

        return $unlinked_demand_array;
    }


	/**
	 * getJobSpeedyDemandArray function.
	 *
	 * Gives an array of the CURRENT demand and allocation for each speedy code that HAS an allocation.
	 *
	 * * It ignores things with no allocation so if the speedy codes does not exist as an array key then the
	 * demand is zero.  Obviously if demand is zero then nothing can be allocated to it.
	 *
	 * @access public
	 * @return array
	 */
	public function getJobSpeedyDemandArray()
    {
	    $speedy_demand_array = array();

        $sql = "SELECT
						`job_master`.`job_speedy_code`,
						`job_demand_master`.`demand`,
        				(
        					SELECT 
        						COUNT(`job_demand_allocation`.`job_demand_master_id`)
        					FROM
        						`job_demand_allocation`
        					WHERE
        						`job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id`
        					AND
		        				`job_demand_allocation`.`status` IN 
		        				(
									'security',
									'review',
									'principal',
									'candidate',
									'pdf',
									'complete'
								)
        				) as `allocated`

        		FROM 
        				`job_demand_master`
                LEFT JOIN
        				`job_master`
        		ON
        				`job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
        		LEFT JOIN
        				`job_demand_allocation`
        		ON
        				`job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`
        		WHERE 
        				`job_demand_master`.`expiry_on` >= NOW()
                AND
                		`job_master`.`job_speedy_code` IN (
                        	SELECT
                            	`job_speedy_code`
                            FROM
                            	`job_speedy`
                        )
               GROUP BY
        				`job_demand_master`.`job_demand_master_id`
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_code,$demand,$allocated);
        $stmt->execute();
        while($stmt->fetch())
        {
	        //demand
	        if( array_key_exists('demand', $speedy_demand_array[$job_speedy_code]) )
	        {
		        $speedy_demand_array[$job_speedy_code]['demand'] += $demand;
	        } else {
		        $speedy_demand_array[$job_speedy_code]['demand'] = $demand;
	        }

	        //allocated
	        if( array_key_exists('demand', $speedy_demand_array[$job_speedy_code]) )
	        {
		        $speedy_demand_array[$job_speedy_code]['allocated'] += $allocated;
	        } else {
		        $speedy_demand_array[$job_speedy_code]['allocated'] = $allocated;
	        }
	    }

        $stmt->close();
        return $speedy_demand_array;

    }

    /**
     * getJobSpeedyDemandDatatable function.
     *
     * Gives an array of the CURRENT demand and allocation for each speedy code that HAS an allocation.
     *
     * * It ignores things with no allocation so if the speedy codes does not exist as an array key then the
     * demand is zero.  Obviously if demand is zero then nothing can be allocated to it.
     *
     * @access public
     * @return array
     */
    public function getJobSpeedyDemandDatatable()
    {
        $request = $_POST;
        $table = 'job_speedy';
        $primaryKey = 'job_speedy.job_speedy_code';

        $columns = array(
                array( 'db' => 'job_speedy.job_speedy_code', 'dt' => 'job_speedy_code' ),
                array( 'db' => 'job_speedy.job_title', 'dt' => 'job_title' ),
                array( 'db' => 'job_demand_master.demand', 'dt' => 'demand' ),
                array( 'db' => 'COUNT(`job_demand_allocation`.`job_demand_master_id`)', 'as' => 'allocation', 'dt' => 'allocation' ),
            );

        $limit = $this->limit( $request, $columns );
        $order = $this->order( $request, $columns );

        $join = ' LEFT JOIN
        				`job_master`
        		ON
        				`job_master`.`job_speedy_code` = `job_speedy`.`job_speedy_code` ';
        $join .= ' LEFT JOIN
        				`job_demand_master`
        		ON
        				`job_demand_master`.`job_master_id` = job_master.job_master_id AND `job_demand_master`.`expiry_on` >= NOW()';
        $join .= ' LEFT JOIN
        				`job_demand_allocation`
        		ON
        				`job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id` ';

        $where = $this->filter($request,$columns,$bindings  );
        $group = ' GROUP BY
        				job_speedy.job_speedy_code ';


        $qry1 = "SELECT ".implode(", ", self::pluck($columns, 'db'))."
             FROM `$table`
             $join
             $where
             
             $group
             $order
             $limit";
        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT({$primaryKey}) as total
			 FROM   `$table`
			  $join
			 $where
			 $group";
        $resFilterLength = $this->db->query_array($qry);
        $recordsFiltered = $resFilterLength[0]['total'];

        // Total data set length
        $qry = "SELECT COUNT({$primaryKey}) as total
             FROM   `$table`  $join $group";
        $resTotalLength = $this->db->query_array($qry);
        $recordsTotal = $resTotalLength[0]['total'];

        return array(
            "draw"            => isset ( $request['draw'] ) ?
                intval( $request['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => $this->data_output( $columns, $data ),
        );

    }

    /**
     * getJobMasterArrayForJobSpeedyCode function.
     *
     * Returns an array of the job master id's that are associated with a particular job speedy codes
     *
     * @access public
     * @param str $job_speedy_code
     * @return arr
     */
    public function getJobMasterArrayForJobSpeedyCode($job_speedy_code)
    {
        $job_master_id_array = array();

        $sql = "SELECT 
                            `job_master`.`job_master_id`,
                            `job_master`.`principal_code`,
                            `job_master`.`brand_code`,
                            `job_master`.`job_code`,
                            `job_master`.`job_title`,
                            `job_demand_master`.`demand`,
        					(
	        					SELECT 
	        						COUNT(`job_demand_allocation`.`job_demand_master_id`)
	        					FROM
	        						`job_demand_allocation`
	        					WHERE
	        						`job_demand_allocation`.`job_demand_master_id` = `job_demand_master`.`job_demand_master_id`
	        					AND
			        				`job_demand_allocation`.`status` IN 
			        				(
										'security',
										'review',
										'principal',
										'candidate',
										'pdf',
										'complete'
									)
	        				) as `allocated`	
                    FROM 
                            `job_master`
                    LEFT JOIN `job_demand_master` ON `job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
                        
                    LEFT JOIN `job_demand_allocation` ON `job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`
                    
                    WHERE 
                            `job_demand_master`.`expiry_on` >= NOW() 
                    AND 
                    		`job_speedy_code` = ?
                    GROUP BY
                            `job_demand_master`.`job_demand_master_id`           	
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$job_speedy_code);
        $stmt->bind_result($job_master_id,$principal_code,$brand_code,$job_code,$job_title, $demand, $allocated);
        $stmt->execute();
        while($stmt->fetch())
        {
            $job_master_id_array[$job_master_id] = array(
                'principal_code' => $principal_code,
                'brand_code' => $brand_code,
                'job_code' => $job_code,
                'job_title' => $job_title,
                'demand' => $demand,
                'allocated' => $allocated,
            );
        }
        $stmt->close();

        return $job_master_id_array;
    }

}    
?>