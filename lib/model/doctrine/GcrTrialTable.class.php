<?php

class GcrTrialTable extends Doctrine_Table
{
    public static function isInstitutionTrial($institution_id)
    {
        $trialRecords = Doctrine::getTable('GcrTrial')->findByOrganizationId($institution_id);

        foreach ($trialRecords as $record)
        {
            if ($record->getEndDate() == 0 || $record->getEndDate() > time())
            {
                return true;
            }		
        }
        return false;
    }
    public static function executeTrialCron()
    {
        $q = Doctrine_Query::create()
	->from('GcrTrial t')
	->where('t.end_date != ?', 0)
	->addWhere('t.end_date > ?', time());

        $activetrials = $q->execute();

        $day = 24 * 60 * 60;
        define ("DAY", $day);

        foreach($activetrials as $trial)
        {
            $start_date = $trial->getStartDate();
            $exp_time = $trial->getExpirationTime();
            $days_remaining = $trial->getDaysRemaining();
            $total_time = $exp_time - $start_date;
            $total_days = floor($total_time / DAY);
            $halfway_days = floor($total_time / DAY / 2);
            $institution = $trial->getInstitution();
            $eschool = $institution->getDefaultEschool();
            if ($mhr_user = $institution->getOwnerUser())
            {
                $user_email = trim($mhr_user->getObject()->email);
            }
            else
            {
                continue;
            }

            $params = array('eschool_short_name' => $institution->getShortName(),
                            'eschool_full_name' => $institution->getFullName(),
                            'default_eschool_url' => $eschool->getAppUrl(),
                            'trial_length' => $total_days);
            switch ($days_remaining)
            {
                case $halfway_days:
                    $params['time_left_text'] = 'has 15 days left.';
                    $params['directions_text'] = 'If you would like to extend your trial or would like to have a tour of a fully built eSchool';
                    $email = new GcrUserEmailer('trial_update', $mhr_user, "Your $total_days-day eSchool trial is now half over.", $params);
                    $email->sendHtmlEmail();
                    print "\n" . $user_email . " was sent 'trial halfway point' email.";
                break;
                /*case 14:
                    $params['time_left_text'] = 'almost over -- you have two weeks left!';
                    $params['directions_text'] = 'If you would like to extend your trial for a short period or need any assistance in moving forward';
                    $email = new GcrUserEmailer('trial_update', $mhr_user, "Your $total_days-day eSchool trial has two weeks left!", $params);
                    $email->sendHtmlEmail();
                    print "\n" . $user_email . " was sent 'trial two-week warning' email.";
                break;
                case 7:
                    $params['time_left_text'] = 'almost over -- you have one week left!';
                    $params['directions_text'] = 'If you would like to extend your trial for a short period or need any assistance in moving forward';
                    $email = new GcrUserEmailer('trial_update', $mhr_user, "Your $total_days-day eSchool trial has one week left!", $params);
                    $email->sendHtmlEmail();
                    print "\n" . $user_email . " was sent 'trial one-week warning' email.";
                break;*/
                case 1:
                    $params['time_left_text'] = 'will expire tomorrow!';
                    $params['directions_text'] = 'If you would like to extend your trial or would like to have a tour of a fully built eSchool';
                    $email = new GcrUserEmailer('trial_update', $mhr_user, "Your $total_days-day eSchool trial expires tomorrow!", $params);
                    $email->sendHtmlEmail();
                    print "\n" . $user_email . " was sent 'trial expires tomorrow' email.";
                break;
                case -2:
                //case -7:
                //case -14:
                //case -21:
                //case -28:
                    $email = new GcrUserEmailer('trial_expired', $mhr_user, "Your $total_days-day eSchool trial has expired!", $params);
                    $email->sendHtmlEmail();
                    print "\n" . $user_email . " was sent 'trial expired' email.";
                break;
            }
        }
    }
}
