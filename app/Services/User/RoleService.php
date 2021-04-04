<?php

namespace App\Services\User;

use App\Entities\Message;
use App\Models\User\Role;
use App\Services\Base\BaseService;
use App\Traits\ApiResponse;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Response;

class RoleService extends BaseService implements RoleInterface
{
    use ApiResponse;

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    protected $fieldSearchable = [];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Role::class;
    }

}
