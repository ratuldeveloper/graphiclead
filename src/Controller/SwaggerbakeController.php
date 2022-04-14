<?php
declare(strict_types=1);

namespace App\Controller;

use SwaggerBake\Lib\Attribute as OpenApi;

/**
 * Swaggerbake Controller
 *
 * @method \App\Model\Entity\Swaggerbake[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SwaggerbakeController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     */
    #[OpenApi\OpenApiPaginator()]
    public function index()
    {
        $this->request->allowMethod('get');
        $swaggerbake = $this->paginate($this->Swaggerbake);

        $this->set(compact('swaggerbake'));
        $this->viewBuilder()->setOption('serialize', 'swaggerbake');
    }

    /**
     * View method
     *
     * @param string|null $id Swaggerbake id.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     */
    public function view($id = null)
    {
        $this->request->allowMethod('get');

        $swaggerbake = $this->Swaggerbake->get($id, [
            'contain' => [],
        ]);

        $this->set('swaggerbake', $swaggerbake);
        $this->viewBuilder()->setOption('serialize', 'swaggerbake');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Exception
     */
    public function add()
    {
        $this->request->allowMethod('post');
        $swaggerbake = $this->Swaggerbake->newEmptyEntity();
        $swaggerbake = $this->Swaggerbake->patchEntity($swaggerbake, $this->request->getData());
        if ($this->Swaggerbake->save($swaggerbake)) {
            $this->set('swaggerbake', $swaggerbake);
            $this->viewBuilder()->setOption('serialize', 'swaggerbake');
            return;
        }
        throw new \Exception("Record not created");
    }

    /**
     * Edit method
     *
     * @param string|null $id Swaggerbake id.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Exception
     */
    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $swaggerbake = $this->Swaggerbake->get($id, [
            'contain' => [],
        ]);
        $swaggerbake = $this->Swaggerbake->patchEntity($swaggerbake, $this->request->getData());
        if ($this->Swaggerbake->save($swaggerbake)) {
            $this->set('swaggerbake', $swaggerbake);
            $this->viewBuilder()->setOption('serialize', 'swaggerbake');
            return;
        }
        throw new \Exception("Record not saved");
    }

    /**
     * Delete method
     *
     * @param string|null $id Swaggerbake id.
     * @return \Cake\Http\Response|null|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     * @throws \Cake\Http\Exception\MethodNotAllowedException
     * @throws \Exception
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['delete']);
        $swaggerbake = $this->Swaggerbake->get($id);
        if ($this->Swaggerbake->delete($swaggerbake)) {
            return $this->response->withStatus(204);
        }
        throw new \Exception("Record not deleted");
    }
}
