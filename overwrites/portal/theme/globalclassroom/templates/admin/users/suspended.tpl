{include file="header.tpl"}

            {$typeform|safe}
            {$buttonformopen|safe}
            {$buttonform|safe}
            <table id="suspendedlist" class="table fullwidth">
                <thead>
                    <tr>
                        <th>{str tag=fullname}</th>
                        <th>{str tag=institution}</th>
                        <th>{str tag=studentid}</th>
                        <th>{str tag=suspendingadmin section=admin}</th>
                        <th>{str tag=suspensionreason section=admin}</th>
                        <th>{str tag=expired section=admin}</th>
                        <th>{str tag=select}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </form>

{$app = gcr::getApp()}
{if !$app->hasPrivilege('GCUser')}
    {literal}
        <script type="text/javascript">
            jQuery('#buttons_delete_container').hide();
        </script>
    {/literal}
{/if}

{include file="footer.tpl"}
