<?php namespace App\Models\Traits;


Trait FromTrait{

    private $original_table;

    public function from($as, $table = null)
    {
        $as  = trim($as);

        $this->rebackTable();

        if(is_null($table)){
            $table = $this->table;
        }

        $this->table = $as;

        return parent::from($table . ' as ' . $as);
    }

    private function rebackTable()
    {
        $this->table = $this->original_table ? : $this->table;

        $this->original_table = $this->table;
    }
}