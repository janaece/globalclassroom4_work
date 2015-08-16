<?php
// Course class.
// Ron Stewart
// October 13, 2010
//
// This class represents a course from a mdl_user table of a given $this->eschool, and offers methods
// to manipulate this course, without the need to actually be logged in to that course's eschool. 
class GcrMdlCourse extends GcrMdlTableRecord
{
    protected $course_collection;
    protected $profile_block;
    
    public function getContext()
    {
        if(isset($this->obj))
        {
            $sql = 'select * from ' . $this->app->getShortName() .
                '.mdl_context where contextlevel = ? and instanceid = ?';
            $mdl_context = $this->app->gcQuery($sql, array(50, $this->obj->id), true);
            return $mdl_context;
        }
    }
    public function getCost($plugin = 'globalclassroom')
    {
        $mdl_enrol = $this->getMdlEnrol($plugin);
        if ($mdl_enrol)
        {
            return $mdl_enrol->cost;
        }
        return false;
    }
    public function getCreditHours()
    {
        $mdl_certificates = $this->app->selectFromMdlTable('certificate', 'course', $this->obj->id);
        if ($mdl_certificates)
        {
            foreach ($mdl_certificates as $mdl_certificate)
            {
                if ($mdl_certificate->printhours != '')
                {
                    return $mdl_certificate->printhours;
                }
            }
        }
        return false;
    }
    // This code is a modified version of what existed in gradelib.php function get_grade_letters()
    public function getGradeLetters()
    {
        $mdl_context = $this->getContext();
        return $this->app->getGradeLetters($mdl_context);
    }
    public function getMdlCategory()
    {
        return $this->app->selectFromMdlTable('course_categories', 'id', $this->obj->category, true);
    }
    public function getCategory()
    {
        $category = $this->getMdlCategory();
        if ($category)
        {
            return new GcrMdlCourseCategory($category, $this->app);
        }
        return false;
    }
    public function getCourseCollection($refresh = false)
    {
        if ($refresh || (!isset($this->course_collection)))
        {
            $course_category = $this->getCategory();
            $this->course_collection = false;
            if ($course_category)
            {
                $this->course_collection = GcrCourseCollection::getInstance($course_category);
            }
        }
        return $this->course_collection;
    }
    public function getDescription()
    {
        $baseurl = $this->app->getAppUrl() . '/pluginfile.php/' . 
                $this->getContext()->id . '/course/summary/';
        return str_replace('@@PLUGINFILE@@/', $baseurl, $this->obj->summary);

    }
    public function getMdlBlockCourseProfile()
    {
        return $this->app->selectFromMdlTable('block_course_profile', 'courseid', $this->obj->id, true);
    }
    public function getBlockCourseProfile($refresh = false)
    {
        if (!isset($this->profile_block) || $refresh)
        {
            $this->profile_block = false;
            $mdl_block_course_profile = $this->getMdlBlockCourseProfile();
            if ($mdl_block_course_profile)
            {
                $this->profile_block = new GcrMdlBlockCourseProfile($mdl_block_course_profile, $this->app);
            }
        }
        return $this->profile_block;
    }
    
    public function getMdlEnrol($plugin = 'globalclassroom')
    {
        $sql = 'select * from ' . $this->app->getShortName() . '.mdl_enrol where enrol = ? and courseid = ?';
        return $this->app->gcQuery($sql, array($plugin, $this->obj->id), true);
    }
    public function getActiveUsersInCourse($roleid = 5)
    {   
        $mdl_context = $this->getContext();
        if(isset($mdl_context) && $mdl_context->contextlevel == '50')
        {
            $sql = "SELECT u.id, u.username, u.email, u.firstname, u.lastname, u.mnethostid FROM " . $this->app->getShortName() . ".mdl_user u,
                                  " . $this->app->getShortName() . ".mdl_role_assignments r
                    WHERE u.id=r.userid
                    AND r.contextid = ?
                    AND r.roleid = ?
                    AND u.auth = ?";
            return $this->app->gcQuery($sql, array($mdl_context->id, $roleid, 'mnet'));
        }
        return false;
    }
    public function getCourseListItem()
    {
        return new GcrCourseListItem($this);
    }
    public function getPotentialUsers($roleid = array())
    {
        $mdl_context = $this->getContext();
        $where = '';
        $params = array($mdl_context->id);
        $sql = "SELECT id, username, firstname, lastname, email
        FROM " . $this->app->getShortName() . ".mdl_user
        WHERE id NOT IN (
         SELECT u.id
           FROM " . $this->app->getShortName() . ".mdl_role_assignments r,
                " . $this->app->getShortName() . ".mdl_user u
          WHERE r.contextid = ?
            AND u.id = r.userid ";
        if(!empty($roleid))
        {
            foreach ($roleid as $role)
            {
                $where .= " AND r.roleid = ? ";
                array_push($params, $role);
            }
        }
        $where .= ')';
        $sql .= $where;
        return $this->app->gcQuery($sql, $params);
    }
    public function getSeller()
    {
        if ($mdl_role = $this->app->selectFromMdlTable('role', 'shortname', 'eclassroomcourseowner', true))
        {
            $mdl_context = $this->getContext();
            $sql = 'select * from ' . $this->app->getShortName() . '.mdl_role_assignments where contextid = ? ' .
                    'and roleid = ?';
            if ($mdl_role_assignment = $this->app->gcQuery($sql, array($mdl_context->id, $mdl_role->id), true))
            {
                return $this->app->getUserOnInstitutionFromId($mdl_role_assignment->userid);
            }
        }
        return false;
    }
    public function getUrl()
    {
        return $this->app->getAppUrl() . '/course/view.php?id=' . $this->obj->id;
    }
    public function getMdlInstructors()
    {
        $eschool = $this->getApp();
        $mdl_context = $this->getContext();
        $sql = 'select * from ' . $eschool->getShortName() .
                '.mdl_role_assignments where roleid < 4 and contextid = ? order by timemodified';
        $mdl_role_assignments = $eschool->gcQuery($sql, array($mdl_context->id));
        $instructors = array();
        if ($mdl_role_assignments)
        {
            foreach ($mdl_role_assignments as $mdl_role_assignment)
            {
                $mdl_user_obj = $eschool->selectFromMdlTable('user', 'id', $mdl_role_assignment->userid, true);
                if ($mdl_user_obj)
                {
                    $instructors[] = new GcrMdlUser($mdl_user_obj, $eschool);
                }
            }
        }
        return $instructors;
    }
    public function getInstructor()
    {
        $mdl_user = false;
        $eschool = $this->getApp();
        $mdl_context = $this->getContext();
        $sql = 'select * from ' . $eschool->getShortName() .
                '.mdl_role_assignments where roleid < 4 and contextid = ? order by timemodified';
        $mdl_role_assignments = $eschool->gcQuery($sql, array($mdl_context->id));
        if (count($mdl_role_assignments) == 1) // There is only one instructor, save processing by picking them
        {
            $mdl_user_obj = $eschool->selectFromMdlTable('user', 'id', $mdl_role_assignments[0]->userid, true);
            $mdl_user = new GcrMdlUser($mdl_user_obj, $eschool);
        }
        else if ($mdl_role_assignments > 1) // More than 1 instructor
        {
            if ($this->getBlockCourseProfile()) 
            {
                // If there is an instructor saved in the course profile
                // block, we will try to choose that user, but we must
                // first make sure that this user is still enrolled in
                // the course with a role assignment of roleid < 4. 
                $instructor = $this->profile_block->getInstructor();
                if ($instructor)
                {
                    foreach ($mdl_role_assignments as $mdl_role_assignment)
                    {
                        if ($mdl_role_assignment->userid == $instructor->getObject()->id)
                        {
                            $mdl_user_obj = $eschool->selectFromMdlTable('user', 'id', $mdl_role_assignment->userid, true);
                            $mdl_user = new GcrMdlUser($mdl_user_obj, $eschool);
                            break;
                        }
                    }
                }
            }
            if (!$mdl_user) // We fallback to choosing 1st in the array.
            {
                $mdl_user_obj = $eschool->selectFromMdlTable('user', 'id', $mdl_role_assignments[0]->userid, true);
                $mdl_user = new GcrMdlUser($mdl_user_obj, $eschool);
            }   
        }
        return $mdl_user;
    }
    function getRepresentativeBlockCourseProfile()
    {
        $block_course_profile = false;
        $course_collection = $this->getCourseCollection();
        if ($course_collection)
        {
            $representative = $course_collection->getRepresentativeCourse();
            if ($representative)
            {
                $block_course_profile = $representative->getBlockCourseProfile();
            }
        }
        return $block_course_profile;
    }
    public function canAssignRoles($mhr_user)
    {
        return ($mhr_user->getRoleManager()->hasPrivilege('EschoolAdmin') || 
                $this->isTeacher($mhr_user));
    }
    public function getRoleAssignments($user)
    {
        $mdl_context = $this->getContext();
        $mdl_user = $user->getUserOnEschool($this->app);
        if ($mdl_user)
        {
            return $mdl_user->getRoles($mdl_context);
        }
        return false;
    }
    public function isInstructor(GcrMdlUser $user)
    {
        foreach($this->getMdlInstructors() as $mdl_user)
        {
            if ($mdl_user->id == $user->getObject()->instructorid)
            {
                return true;
            }
        }
        return false;
    }
    public function isVisible()
    {
        return ($this->obj->visible == 1); 
    }
    public function isRepresented()
    {
        $course_collection = $this->getCourseCollection();
        if ($course_collection)
        {
            return (!$this->isRepresentative());           
        }
        return false;
    }
    public function isRepresentative()
    {
        $course_collection = $this->getCourseCollection();
        if ($course_collection)
        {
            $representative = $course_collection->getRepresentativeCourse();
            if ($representative)
            {
                return ($this->obj->id == $representative->getObject()->id);
            }
        }
        return false;
    }
    public function isSelfEnrollable()
    {
        $mdl_enrol = $this->getMdlEnrol();
        return ($mdl_enrol && $mdl_enrol->status == 0 && 
                ($mdl_enrol->enrolenddate == 0 || $mdl_enrol->enrolenddate < time()));
    }
    public function isTeacher($mhr_user)
    {
        $mdl_role_assignments = $this->getRoleAssignments($mhr_user);
        if ($mdl_role_assignments)
        {
            foreach ($mdl_role_assignments as $role)
            {
                if ($role->roleid == 3)
                {
                    return true;
                }
            }
        }
        return false;
    }
    public function addQuotesToSrcString($question_text)
    {
        $end_index = 0;
        $corrected = false; // flag for whether anything was changed.
        while ($index = strpos($question_text, 'src=', $end_index))
        {
            if (substr($question_text, $index += 4, 1) != '"')
            {
                $question_text = substr_replace($question_text, '"', $index, 0);
                if ($index_gt = strpos($question_text, '>', $index))
                {
                    if ($index_ws = strpos($question_text, ' ', $index))
                    {
                        if ($index_ws > $end_index)
                        {
                            $end_index = $index_ws;
                        }
                        else
                        {
                            if (substr($question_text, $index_gt - 1, 1) == '/')
                            {
                                $index_gt--;
                            }
                            $end_index = $index_gt;
                        }
                    }
                    else
                    {
                        $end_index = $index_gt;
                    }
                }
                else if ($index_ws = strpos($question_text, ' ', $index))
                {
                    $end_index = $index_ws;
                }
                else
                {
                    $end_index = $index;
                }
                if ($end_index != $index)
                {
                    if (substr($question_text, $end_index, 1) != '"')
                    {
                        $question_text = substr_replace($question_text, '"', $end_index, 0);
                    }
                }
                $corrected = true;
            }
            else
            {
                $end_index = $index;
            }
        }
        if ($corrected)
        {
            return $question_text;
        }
        return false;
    }
    public function repairTextWithSrcAttribute($text)
    {
        $fixed_text = false;
        if ($fixed_src = $this->addQuotesToSrcString($text))
        {
            $fixed_text = $fixed_src;
            $text = $fixed_text;
        }
        if ($fixed_file_src = $this->fixFileUrl($text, 'src='))
        {
            $fixed_text = $fixed_file_src;
            $text = $fixed_text;
        }
        if ($fixed_file_href = $this->fixFileUrl($text, 'href='))
        {
            $fixed_text = $fixed_file_href;
        }
        if ($fixed_text)
        {
            return $fixed_text;
        }
        return false;
    }
    public function repairQuestionsWithSrcAttribute()
    {
        $repair_count = 0;
        $mdl_question_categories = $this->app->selectFromMdlTable('question_categories',
                'contextid', $this->getContext()->id);
        foreach($mdl_question_categories as $category)
        {
            $mdl_questions = $this->app->selectFromMdlTable('question', 'category', $category->id);
            foreach ($mdl_questions as $question)
            {
                $question_text = $question->questiontext;
                if ($fixed_src = $this->repairTextWithSrcAttribute($question_text))
                {
                    $this->app->updateMdlTable('question', array('questiontext' => $fixed_src),
                            array('id' => $question->id));
                    $repair_count++;
                }
                $question_answers = $this->app->selectFromMdlTable('question_answers', 'question', $question->id);
                foreach($question_answers as $answer)
                {
                    $answer_text = $answer->answer;
                    if ($fixed_src = $this->repairTextWithSrcAttribute($answer_text))
                    {
                        $this->app->updateMdlTable('question_answers', array('answer' => $fixed_src),
                                array('id' => $answer->id));
                        $repair_count++;
                    }
                }
                /*$match_sub_questions = $this->app->selectFromMdlTable('question_match_sub', 'question', $question->id);
                foreach($match_sub_questions as $question)
                {
                    $question_text = $question->questiontext;
                    if ($fixed_src = $this->repairTextWithSrcAttribute($question_text))
                    {
                        $this->app->updateMdlTable('question_match_sub', array('questiontext' => $fixed_src),
                                array('id' => $question->id));
                        $repair_count++;
                    }
                    $answer_text = $question->answertext;
                    if ($fixed_src = $this->repairTextWithSrcAttribute($answer_text))
                    {
                        $this->app->updateMdlTable('question_match_sub', array('answertext' => $fixed_src),
                                array('id' => $question->id));
                        $repair_count++;
                    }
                }*/
            }
        }
        return $repair_count;
    }
    function repairFileReferences($table, $course_column_name, $text_column_name)
    {
        $repair_count = 0;
        $mdl_records = $this->app->selectFromMdlTable($table, $course_column_name, $this->obj->id);
        foreach ($mdl_records as $record)
        {
            $record_text = $record->$text_column_name;
            if ($fixed_src = $this->fixFileUrl($record_text, 'src='))
            {
                $this->app->updateMdlTable($table, array($text_column_name => $fixed_src),
                        array('id' => $record->id));
                $record_text = $fixed_src;
                $repair_count++;
            }
            if ($fixed_href = $this->fixFileUrl($record_text, 'href='))
            {
                $this->app->updateMdlTable($table, array($text_column_name => $fixed_href),
                        array('id' => $record->id));
                $repair_count++;
            }
        }
        return $repair_count;
    }
    function fixFileUrl($text, $attr)
    {
        $end_index = 0;
        $corrected = false; // flag for whether anything was changed.
        while ($index = strpos($text, $attr, $end_index))
        {
            if ($file_index = strpos($text, '/file.php/', $index))
            {
                if ($index_ws = strpos($text, ' ', $index))
                {
                    if (!$file_index > $index_ws)
                    {
                        $end_index = $index + 4;
                        continue;
                    }
                }
                $file_index = strpos($text, '/', $file_index + 11);
                $correct_src = $attr . '"' . $this->app->getAppUrl() . '/file.php/' . $this->obj->id;
                $src = substr($text, $index, $file_index - $index);
                if ($correct_src != $src)
                {
                    $text = substr_replace($text, $correct_src, $index, $file_index - $index);
                    $corrected = true;
                }
            }
            $end_index = $index + 4;
        }
        if ($corrected)
        {
            return $text;
        }
        return false;
    }
}
?>
