<?php


namespace App;


class Question extends Model
{
    protected $tableName = 'questions';

    protected $fillables = [ 'statement', 'options', 'answer', 'level', 'category' ];

    public function getAllQuestions()
    {
        $result = [];
        foreach($this->all() as $key => $one) {
            $result[$key] = $one;
            $result[$key]['options'] = unserialize($one['options']);
        }
        return $result;
    }

    public function getFilteredQuestions($based = 'level')
    {
        foreach($this->getAllQuestions() as $one) {
            if($one['level'] === 'easy') {
                $easy[] = $one;
            }
            if($one['level'] === 'medium') {
                $medium[] = $one;
            }
            if($one['level'] === 'hard') {
                $hard[] = $one;
            }
        }
        return array(
          $easy[array_rand($easy, 1)],
          $medium[array_rand($medium, 1)],
          $hard[array_rand($hard, 1)],
        );
    }

}