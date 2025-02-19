<?php
/* 
Template Name: Task Page
*/

get_header();
?>
<main id="" role="main" data-v-app="">
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
            <div class="col-lg-9 col-md-12" id="task-app">
                
                <!-- Client Content Template -->
                <div class="row" id="client-content" v-if="isClient">
                    <div v-if="allTaxonomies.length > 0" v-for="taxonomy in allTaxonomies" :key="taxonomy.name">
                        <div v-if="taxonomy.name === 'task_status'">
                            <div class="row">
                                <div class="col-md-4" v-for="term in taxonomy.terms" :key="term.slug">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white text-center">
                                            <h3>{{ term.name }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div v-if="tasksByStatus[term.name] && tasksByStatus[term.name].length > 0">
                                                <div v-for="task in tasksByStatus[term.name]" :key="task.id">
                                                    <div class="card mb-3">
                                                        <div class="card-body" :class="getTaskClasses(task)">
                                                            <h4 class="card-title">{{ task.title.rendered || 'No title' }} <span class="text-muted" v-if="task.my_meta.highlight_post === '1'">(highlight)</span></h4>
                                                            <p class="text-muted mb-0"> Priority: {{ task.my_taxonomies.task_priority?.[0]?.term_name ?? 'No Priority' }} </p>
                                                            <p class="text-muted mb-0"> Deadline: {{ task.my_meta.deadline || 'No deadline' }} </p>
                                                            <p class="text-muted mb-0"> Category: {{ task.my_taxonomies.task_category?.[0]?.term_name ?? 'No Category' }} </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Server Content Template-->
                <div class="row" id="server-content" v-else>
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
                        'meta_query'     => array(
                        'relation' => 'OR',
                        array(
                            'key'     => '_deadline',
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key'     => '_deadline',
                            'compare' => 'NOT EXISTS',
                        ),
                    ),
                    'orderby'        => array(
                        'meta_value_num' => 'DESC', // Sort by highlight first
                        'meta_value'     => 'ASC',  // Then by deadline (earliest first)
                        'date'           => 'ASC',  // Then by post date
                    ),
                    'meta_key' => '_is_highlight',
                    );
                    $tasks = get_posts($args);
                    ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h3><?php echo esc_html($term->name); ?></h3>
                            </div>
                            <div class="card-body">
                                <?php if ( !empty($tasks) ) : ?>
                                <?php foreach( $tasks as $task ) :
                                    $is_highlight = get_post_meta($task->ID, '_is_highlight', true) === '1' ? 'highlight' : '';
                                    // Get the task status term
                                    $task_status = wp_get_post_terms($task->ID, 'task_status');
                                    $task_status_slug = !empty( $task_status ) ? $task_status[0]->slug : '';
                
                                    // Conditionally add task status classes
                                    $status_class = match($task_status_slug) {
                                        'to-do' => 'task-todo',
                                        'progress' => 'task-progress',
                                        'done' => 'task-done',
                                        default => '',
                                    };

                                    // Combine the classes
                                    $myclasses = trim("$is_highlight $status_class"); 
                                ?>
                                <div class="card mb-3">
                                    <div class="card-body <?php echo esc_attr($myclasses); ?>">
                                        <h4 class="card-title"><?php echo esc_html($task->post_title); ?> <span class="text-muted"><?php echo $is_highlight ? '(highlight)' : ''; ?></span></h4>
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
                                <?php else : ?>
                                    <div class="text-center text-muted">
                                        No tasks found for <?php echo esc_html($term->name); ?>.
                                    </div>
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
