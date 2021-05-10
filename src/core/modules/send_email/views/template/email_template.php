<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <title><?php echo $subject ?></title>
        
        <style type="text/css">
            
<?php include DIR_LOCAL_UPLOADS.'/send_email/email.css'; ?>
            
        </style>
        
    </head>
    
    <body>
    
    <!-- Using tables for layout to be compatible with most clients -->
        
        <table width="620" cellpadding="0" cellspacing="0" id="bodyTable">
            
            <!-- Header -->
            <tr id="headingRow">
                <th>
<?php
                if(is_readable(DIR_LOCAL_UPLOADS.'/send_email/banner.png'))
                {
?>
                    <img src="<?php echo HTTP_TYPE.SITE_WWW.'/local/uploads/send_email/banner.png'; ?>" alt="Banner" width="620" height="100">
<?php
                } else {
?>
                    <h1><?php echo $subject ?></h1>
<?php
                }
?>
                </th>
            </tr>
            
            <!-- Body -->
            <tr id="bodyRow">
                <td>
                    <div id="emailMessage">
                        <?php echo $message ?>
                    </div>
                </td>
            </tr>
    
            <!-- Footer -->
            <tr id="footerRow">
                <td>            
<?php
                if(empty($unsubscribelink))
                {
?>
                    &nbsp;
<?php
                } else {
?>
                    <div id="unsubscribe">
                        <a href="<?php echo $unsubscribelink; ?>">Unsubscribe</a>
                    </div>
<?php
                }
?>
                </td>
            </tr>
            
        </table>
        
    </body>

</html>