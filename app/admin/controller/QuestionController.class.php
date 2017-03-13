<?php
class QuestionController extends CommonController
{
    public function editAction()
    {
        $this->id = Support::Input('id','get','id');
        if(!$this->id){
            $this->tips(false,'请先保存课程信息！');
            $this->display();
        }

        if(IS_POST){
			$this->_deleteData($this->id);	//删除
			$this->_addData($this->id);		//添加
			$this->_saveData($this->id);		//修改
			$this->tips(true, '保存成功。');
		}

        $this->data = Support::Dtable('question')->getbyCourseId($this->id);
        $this->display();
    }

    public function addtestAction()
    {
        $data = [
            //判断题
            ['course_id' => 1,
            'type' => 'binary',
            'content'=> '1加1等于2（）.',
            'options' => ' ',
            'answer' => 'T'
            ],
            //单选题
            ['course_id' => 1,
            'type' => 'single',
            'content' => '1加1等于2（）.',
            'options' =>serialize(['A'=>'1','B'=>'2','C'=>'3','D'=>'4']),
            'answer'=> 'B'
            ],
            //多选题
            ['course_id' => 1,
            'type' => 'multiple',
            'content' => '1加1不等于（）。',
            'options' => serialize(['A'=>'1','B'=>'2','C'=>'3','D'=>'4']),
            'answer'=> serialize(['A','C','D'])
            ],
            //填空题
            ['course_id' => 1,
            'type' => 'fill',
            'content' => '1加1等于——————。',
            'options' => '',
            'answer' => '2'
            ],
        ];
        var_dump($data);
        Support::Dtable('question')->add($data);
        echo "成功";
    }


    private function _addData($course_id){
		$result = [];
		foreach(Support::Input('add', 'post', 'array') as $v){
			$result[] = $this->_formatData($v, $course_id);
		}
		empty($result) || Support::Dtable('question')->add($result);
	}

	private function _saveData($course_id){
		$result = [];
		foreach(Support::Input('save', 'post', 'array') as $k=>$v){
			$result[] = array_merge($this->_formatData($v, $course_id), ['id'=>Support::Input(null, null, 'id', $k)]);
		}
		empty($result) || Support::Dtable('question')->save($result, 'id,course_id');
	}

	//格式化每道题
	private function _formatData($data, $course_id){
		$result = [
			'type' => Support::Input('type', $data, 'string'),
			'content' => Support::Input('content', $data, 'html'),
			'answer' => Support::Input('answer', $data, ''),
			'options' => Support::Input('options', $data, ''),
			'course_id' => $course_id
		];
		switch($result['type']){
			case 'binary':
				if(!in_array($result['answer'], ['T','F'])){
					$result['answer'] = '';
				}
				$result['options'] = '';
			break;
			case 'single':
				if(!in_array($result['answer'], ['A','B','C','D'])){
					$result['answer'] = '';
				}
				$result['options'] = $this->_formatOption($result['options']);
			break;
			case 'multiple':
				if(!is_array($result['answer']) || count($result['answer']) < 1){
					$result['answer'] = [];
				}
				$result['answer'] = serialize(array_intersect(['A','B','C','D'], $result['answer']));
				$result['options'] = $this->_formatOption($result['options']);
			break;
			case 'fill':
				$result['options'] = '';
				$result['answer'] = Support::Input(null, null, 'html', $result['answer']);
			break;
		}
		return $result;
	}

	//格式化选项
	private function _formatOption($data){
		$result = [];
		foreach(['A', 'B', 'C', 'D'] as $v){
			$result[$v] = Support::Input($v, $data, 'html');
		}
		return serialize($result);
	}

	//删除习题
	private function _deleteData($course_id){
		$result = [];
		foreach(Support::Input('del', 'post', 'array') as $v){
			$result[] = [
				'id' => Support::Input(null, null, 'id', $v),
				'course_id' => $course_id
			];
		}
		empty($result) || Support::Dtable('question')->delete($result);
	}
}
