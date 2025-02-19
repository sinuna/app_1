document.addEventListener('DOMContentLoaded', function () {
    const taskApp = Vue.createApp({
        data() {
            return {
                isClient: true,
                restUrl: ajax_object.rest_url,
                allTaxonomies: [],
                taskPosts: [],
                tasksByStatus: {}, // Object to store tasks grouped by status
            };
        },
        mounted() {
            this.fetchAllTaxonomies();
            this.fetchTaskPost();
        },
        methods: {
            fetchTaxonomyTerms( taxonomy ) {
                const taxonomyUrl = `${this.restUrl}custom/v1/task/${taxonomy}`;
                $.ajax({
                    url: taxonomyUrl,
                    method: 'GET',
                    success: ( res ) => {
                        console.log('res', res);
                        if ( res && Array.isArray(res) ) {
                            const existingTaxonomy = this.allTaxonomies.find( item => item.name === taxonomy);

                            if ( existingTaxonomy ) {
                                existingTaxonomy.terms = res;
                            } else {
                                this.allTaxonomies.push({
                                    name: taxonomy,
                                    terms: res,
                                })
                            }
                        } else {
                            console.warn('Unexpected response format for taxonomy:', taxonomy);
                        }
                    },
                    error: (error) => {
                        console.error('Error fetching taxonomy terms:', error);
                    }
                })
            },
            fetchAllTaxonomies() {
                const taxonomies = ['task_status', 'task_priority', 'task_category']; // Add more taxonomies if needed
                taxonomies.forEach( taxonomy => {
                    this.fetchTaxonomyTerms(taxonomy);// Fetch terms for each taxonomy
                });
            },
            fetchTaskPost() {
                const taskUrl = `${this.restUrl}wp/v2/task`;
                $.ajax({
                    url: taskUrl,
                    method: 'GET',
                    dataType: 'json', // Ensures response is parsed as JSON
                    success: (res) => {
                        this.taskPosts = res;
                        console.log('All Tasks', this.taskPosts);
                        this.groupTasksByStatus();
                    },
                    error: (error) => {
                        console.error('Error fetching task data:', error);
                    },
                });
            },
            groupTasksByStatus() {
                this.tasksByStatus = {}; // Initialize the object to store tasks grouped by status

                this.taskPosts.forEach((task) => {
                    const status = task.my_taxonomies?.task_status?.[0]?.term_name;

                    if ( status ) {
                        if ( !this.tasksByStatus[status] ) {
                            this.tasksByStatus[status] = [];
                        }
                    }

                    this.tasksByStatus[status].push(task);

                });
            
                console.log('Grouped Tasks by Status:', this.tasksByStatus);
            },
            getTaskClasses(task) {
                const classes = [];
                if (task.my_meta.highlight_post === '1') classes.push('highlight');
    
                const statusClasses = {
                    'to-do': 'task-todo',
                    'progress': 'task-progress',
                    'done': 'task-done'
                };
            
                const taskStatus = task.my_taxonomies.task_status?.[0]?.term_slug;
                if (statusClasses[taskStatus]) classes.push(statusClasses[taskStatus]);
            
                return classes.join(' ');
            },
        }
    });

    taskApp.mount('#task-app');
});
