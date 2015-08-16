<?php 
// Template email for verifying a new institution. 
?>
<p>
    Your free trial Global Classroom Stratus Platform is ready to be created!
</p>
<p>
    To verify your email address, please click the link below.
</p>
<p>
    <a href="<?php print GcrInstitutionTable::getHome()->getUrl() . '/institution/verify?aid=' .
        $this->params['application']->getId() . '&verify=' . $this->params['application']->getVerifyHash(); ?>">
        <?php print GcrInstitutionTable::getHome()->getUrl() . '/institution/verify?aid=' .
        $this->params['application']->getId() . '&verify=' . $this->params['application']->getVerifyHash(); ?>
    </a>
</p>
<p> </p>
<br />
<?php print $this->params['powered_by_GC']; ?>
