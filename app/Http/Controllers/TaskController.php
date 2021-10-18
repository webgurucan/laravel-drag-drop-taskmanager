<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tasks = Task::getAllTasks();
        return view('task.index', compact('tasks'));
    }

    /**
     * Get special task info
     *
     * @param  int  $id
     * @return json format response
     */
    public function info($id)
    {
        $task = Task::find($id);
        if ($task) {
            $response = [
                'status' => 'success',
                'data' => $task->toArray()
            ];
        }
        else {
            $response = [
                'status' => 'error',
                'message' => 'There is no such task.'
            ];
        }
        return response()->json($response);
    }

    /**
     * Add / Update / Reorder task
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return json format response
     */
    public function update(Request $request)
    {
        $param = $request->all();
        
        $response = [
            'status' => 'error',
            'message' => 'Something went wrong.'
        ];

        if (isset($param['action']) && $param['action'] == 'priority') {
            //You are going to update task priority by drag and drop
            foreach($param['order'] as $row) {
                Task::where('id', $row['id'])->update(['priority' => $row['priority']]);
            }

            $response = [
                'status' => 'success',
                'message' => 'Priority has been updated successfully.',
                'html' => view('task.partials.list', ['tasks' => Task::getAllTasks()])->render()
            ];
        }
        else {
            $param = array_map('trim', $param);
            if (empty($param['id'])) {
                //You are going to add new task
                $task = Task::getTaskByName($param['name']);
                if ($task) {
                    //Duplication with name
                    $response = [
                        'status' => 'error',
                        'message' => 'A duplicated task exists',
                    ];
                }
                else {
                    $task = new Task;
                    $task->name = $param['name'];
                    $task->priority = $param['priority'];

                    if ($task->save()) {
                        $response = [
                            'status' => 'success',
                            'message' => 'You have added a task successfully.',
                            'html' => view('task.partials.list', ['tasks' => Task::getAllTasks()])->render()
                        ];
                    }
                }
            }
            else {
                //You are going to update existing task
                if (Task::isDuplicated($param['id'], $param['name'])) {
                    //Duplicated
                    $response = [
                        'status' => 'error',
                        'message' => 'A duplicated task exists.',
                    ];
                }
                else {
                    $task = Task::find($param['id']);
                    if ($task) {
                        $task->name = $param['name'];
                        $task->priority = $param['priority'];
                        if ($task->save()) {
                            $response = [
                                'status' => 'success',
                                'message' => 'You have updated a task successfully.',
                                'html' => view('task.partials.list', ['tasks' => Task::getAllTasks()])->render()
                            ];
                        }
                    }
                    else {
                        //No such task
                        $response = [
                            'status' => 'error',
                            'message' => 'There is no such task.',
                        ];
                    }
                }
            }
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $response = [];
        if ($task) {
            if ($task->delete()) {
                $response = [
                    'status' => 'success',
                    'message' => 'You have deleted a task successfully.'
                ];
            }
            else {
                $response = [
                    'status' => 'error',
                    'message' => 'Something went wrong.'
                ];
            }
        }
        else {
            $response = [
                'status' => 'error',
                'message' => 'There is no such task.'
            ];
        }

        return response()->json($response);
    }
}
