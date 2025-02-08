<?php
/* 
Template Name: Task Page
*/

get_header();
?>
<main id="task-app" role="main" data-v-app="">
    <div class="container my-4">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="create-task mb-4" aria-labelledby="add-new-task-heading">
                    <form class="d-flex flex-column gap-4" style="border: 1px solid rgb(204, 204, 204); border-radius: 10px; padding: 20px;">
                        <h2>Add New Task</h2>
                        <div class="form-group d-flex align-items-center gap-2">
                            <label for="title">Title: </label>
                            <input type="text" class="form-control" id="title" aria-describedby="taskName" placeholder="Enter task name" required=""></div>
                        <div class="form-group d-flex align-items-center gap-2">
                            <label for="task_category" class="text-capitalize">task category:</label>
                            <select id="task_category">
                                <option value="">Select an option</option>
                                <option value="2">Work</option>
                                <option value="3">Personal</option>
                            </select>
                        </div>
                        <div class="form-group d-flex align-items-center gap-2">
                            <label for="task_priority" class="text-capitalize">task priority:</label>
                            <select id="task_priority">
                                <option value="">Select an option</option>
                                <option value="4">Low</option>
                                <option value="5">Medium</option>
                                <option value="6">High</option>
                            </select>
                        </div>
                        <div class="form-group d-flex align-items-center gap-2">
                            <label for="task_status" class="text-capitalize">task status:</label>
                            <select id="task_status">
                                <option value="">Select an option</option>
                                <option value="7">To do</option>
                                <option value="8">Progress</option>
                                <option value="9">Done</option>
                            </select>
                        </div>
                        <div class="form-group d-flex align-items-center gap-2">
                            <label for="deadline">Deadline:</label>
                            <input type="date" id="deadline" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-dark text-white">Submit</button>
                    </form>
                </div>
                <div class="filter mb-4" aria-labelledby="filter-heading">
                    <form style="border: 1px solid rgb(204, 204, 204); border-radius: 10px; padding: 20px;">
                        <h2>Filter</h2>
                        <div class="mb-4">
                            <h5>Task Category</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="checkbox" id="work" aria-labelledby="task-filter" value="work">
                                <label for="work">Work</label>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="checkbox" id="personal" aria-labelledby="task-filter" value="personal">
                                <label for="personal">Personal</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5>Task Priority</h5>
                            <div class="d-flex gap-2 align-items-center"><input type="checkbox" id="low" aria-labelledby="task-filter" value="low">
                                <label for="low">Low</label>
                            </div>
                            <div class="d-flex gap-2 align-items-center"><input type="checkbox" id="medium" aria-labelledby="task-filter" value="medium">
                                <label for="medium">Medium</label>
                            </div>
                            <div class="d-flex gap-2 align-items-center"><input type="checkbox" id="high" aria-labelledby="task-filter" value="high">
                                <label for="high">High</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5>Task Status</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="checkbox" id="to-do" aria-labelledby="task-filter" value="to-do"><label for="to-do">To do</label>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="checkbox" id="progress"aria-labelledby="task-filter" value="progress"><labelfor="progress">Progress</label>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="checkbox" id="done" aria-labelledby="task-filter" value="done"><label for="done">Done</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="row" id="server-content">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h3>To do</h3>
                            </div>
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title">Shopping <span class="text-muted">(highlight)</span></h4>
                                        <p class="text-muted mb-0"> Priority: Low </p>
                                        <p class="text-muted mb-0"> Deadline: 2025-07-23 </p>
                                        <p class="text-muted mb-0"> Category: Personal </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h3>Progress</h3>
                            </div>
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title">Attent event <span class="text-muted"></span></h4>
                                        <p class="text-muted mb-0"> Priority: Low </p>
                                        <p class="text-muted mb-0"> Deadline: 2025-01-30 </p>
                                        <p class="text-muted mb-0"> Category: Work </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h3>Done</h3>
                            </div>
                            <div class="card-body">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title">Swimming Class <span class="text-muted"></span></h4>
                                        <p class="text-muted mb-0"> Priority: Medium </p>
                                        <p class="text-muted mb-0"> Deadline: 2025-01-16 </p>
                                        <p class="text-muted mb-0"> Category: Personal </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
