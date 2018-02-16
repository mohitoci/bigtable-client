<?php
/*
 * Copyright 2017 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Tests\Unit\Bigtable;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';

putenv('GOOGLE_APPLICATION_CREDENTIALS=../Grass_Clump_479-b5c624400920.json');

use Google\Cloud\Bigtable\src\BigtableInstance;
use PHPUnit\Framework\TestCase;
use Google\GAX\ValidationException;
use Google\GAX\OperationResponse;
use Google\Bigtable\Admin\V2\Instance;
use Google\Bigtable\Admin\V2\Instance_Type;
use Google\Protobuf\GPBEmpty;
use Google\Bigtable\Admin\V2\ListInstancesResponse;

use Prophecy\Argument;

class InstanceTest extends TestCase
{
    const PROJECT_ID = 'grass-clump-479';
    const INSTANCE_ID = 'my-instance';

    public $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder(BigtableInstance::class)
                            ->disableOriginalConstructor()
                            ->getMock();
    }

    public function testListInstances()
    {
        $ListInstances = new ListInstancesResponse();
        $this->mock->method('listInstances')
             ->willReturn($ListInstances);
        $instances = $this->mock->listInstances();
        $this->assertInstanceOf(ListInstancesResponse::class, $instances);
    }
    
    public function testUpdateInstance()
	{   
        $parent = 'projects/'.self::PROJECT_ID.'/instances/'.self::INSTANCE_ID;
        $displayName = 'my-instance2';
        $Instance = new Instance();
        $Instance->setName($parent);
        $Instance->setDisplayName($displayName);
        $Instance->setType(Instance_Type::DEVELOPMENT);

        $this->mock->method('updateInstance')
             ->willReturn($Instance);

        $instance = $this->mock->updateInstance(Argument::type('string'), Argument::type('integer'));
        $this->assertEquals($instance->getDisplayName(), $displayName);
        $this->assertInstanceOf(Instance::class, $instance);
	}
    
    public function testDeleteInstance()
    {
        $GPBEmpty = new GPBEmpty();
        $this->mock->method('deleteInstance')
             ->willReturn($GPBEmpty);
        
        $res = $this->mock->deleteInstance();
        $this->assertInstanceOf(GPBEmpty::class, $res);
    }
}
