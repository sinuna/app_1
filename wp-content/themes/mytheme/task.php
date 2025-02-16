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
                    <?php
                    $taskStatusTerms = get_terms(array( // Get all terms for task_status taxonomy
                        'taxonomy' => 'task_status',
                        'hide_empty' => false, 
                        'orderby' => 'term_id',
                        'order' => 'ASC'
                    ));
                    
                    foreach( $taskStatusTerms as $term ) : 
                    $args = array( // Get all tasks for the current term
                        'post_type' => 'task',
                        'posts_per_page' => -1, // Retrieve all tasks
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'task_status',
                                'field' => 'term_id',
                                'terms' => $term->term_id
                            )
                        ),
                        'order_by' => array(
                            'date' => 'ASC'
                        )
                    );
                    $tasks = get_posts($args);
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h3><?php echo esc_html($term->name); ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if ( !empty($tasks) ) :?>
                                <?php foreach( $tasks as $task ) : ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h4 class="card-title"><?php echo esc_html($task->post_title); ?> <span class="text-muted">(highlight)</span></h4>
                                        <p class="text-muted mb-0">
                                            <?php $priorities = wp_get_post_terms($task->ID, 'task_priority', ['fields' => 'names']);                                        
                                            echo 'Priority: ' . (!empty($priorities) ? esc_html(implode(', ', $priorities)) : 'No Priority');  
                                            ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <?php echo 'Deadline: ' . (esc_html(get_post_meta($task->ID, '_deadline', true)) ?: 'No deadline'); ?>
                                        </p>
                                        <p class="text-muted mb-0">
                                            <?php $categories = wp_get_post_terms($task->ID, 'task_category', ['fields' => 'names']);                                        
                                            echo 'Category: ' . (!empty($categories) ? esc_html(implode(', ', $categories)) : 'No Category');  
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
