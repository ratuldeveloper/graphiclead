<?php
declare(strict_types=1);
namespace App\Model\Table;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ImagesQrTable extends Table {

    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('images_queue');
        $this->setPrimaryKey('id');
        $this->hasMany('UsersTable',[
            'foreignKey' => 'user_id',
        ]);
    }
    
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');
        return $validator;
    }

}
