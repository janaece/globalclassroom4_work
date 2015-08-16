<?php global $CFG; ?>
<?php print $this->params['header_image']; ?>
<p>
    You have received a new message sent to your GlobalClassroom Platform, <a href="<?php print $this->params['eschool_url']; ?>"><?php print $this->params['eschool_full_name']; ?></a>.
    This email was automatically generated and sent by GlobalClassroom Inc.
</p>
<p>
    <br />
    <small>--- Start of Message ---</small>
    <br />
    <br />
    <table>
        <tr>
            <td>From:</td><td><?php print $this->params['from_name']; ?></td>
        <tr>
            <td>Email:</td><td><?php print $this->params['from_email']; ?></td>
        <tr>
            <td>Subject:</td><td><?php print $this->params['subject']; ?></td>
        </tr>
    </table>
    <br />
    <?php print $this->params['message']; ?>
    <br />
    <br />
    <small>--- End of Message ---</small>
    <br />
</p>
<p>
    <?php print $this->params['powered_by_GC']; ?>
</p>