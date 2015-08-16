<script type='text/javascript' src='/js/jquery.dataTables.min.js'></script>
<style type="text/css">@import "//ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css";</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<style type="text/css">@import "/css/dataTables.css";</style>


<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function() {
            var oTable = jQuery('#userSearchTable').dataTable( {
                    "bProcessing": true,
                    "sAjaxSource": "getUserData",
                    "bJQueryUI": true,
                    "sPaginationType": "full_numbers",
                    "bServerSide": true
            } );
    } );
</script>

<div style="padding:10px">
    <h2>GlobalClassroom Systemwide User Listing<span style="float:right"><a href="/stratus">Exit</a></span></h2>    
    <div id="dynamic">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="display" id="userSearchTable">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Platform</th>
                    <th>Last Access</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Platform</th>
                    <th>Last Access</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="spacer"></div>
</div>