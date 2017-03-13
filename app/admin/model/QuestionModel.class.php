<?php
class QuestionModel extends Model
{
    public function getbyCourseId($course_id)
    {
        $data = $this->select('id,type,content,options,answer', ['course_id' => $course_id]);
        $result = ['binary' => [], 'single' => [], 'multiple'=> [], 'fill' => [] ];
        foreach($data as $k=>$v){
            switch($v['type']){
                case 'single':
                    $v['options'] = unserialize($v['options']);
                    break;
                case 'multiple':
                    $v['answer'] = unserialize($v['answer']);
                    $v['options'] = unserialize($v['options']);
                    break;
            }
            $result[$v['type']][$v['id']] = $v;
        }
        return $result;
    }
}
