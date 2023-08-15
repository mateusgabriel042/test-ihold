<?php

namespace App\Services;

use Illuminate\Http\Request;

class AbstractService
{
  private $model;

  public function __construct($model)
  {
    $this->model = $model;
  }

  public function getData()
  {
    $data = [
      'count' => $this->model->all()->count(),
    ];
    return $data;
  }

  public function getAll($relations = [])
  {
    return $this->model->with($relations)->orderBy('created_at', 'DESC')->paginate(20);
  }

  public function getAllNotPaginate($relations = [], $colOrderBy = null)
  {
    if ($colOrderBy == null)
      return $this->model->with($relations)->orderBy('created_at', 'DESC')->get();
    else
      return $this->model->with($relations)->orderBy($colOrderBy, 'DESC')->get();
  }

  public function create($request)
  {
    $dataRequest = $request->all();
    $objectRegistered = $this->model->create($dataRequest);
    return $objectRegistered;
  }

  public function find($id, $relations = [])
  {
    return $this->model->with($relations)->find($id);
  }

  public function search($option, $value, $relations = [])
  {
    $objectsSelected = $this->model->with($relations)->where($option, 'LIKE', "%{$value}%")->paginate(20);
    return $objectsSelected;
  }

  public function update($request, $id)
  {
    $objectEdit = $this->model->find($id);
    $dataRequest = $request->all();
    $objectEdit->update($dataRequest);
    return $objectEdit;
  }

  public function delete($id)
  {
    $objectRegistered = $this->model->find($id);
    $objectRegistered->delete();
    return $objectRegistered;
  }

  public function multipleDeletion($ids = [])
  {
    $result = $this->model->destroy($ids);
    return $result;
  }
}
