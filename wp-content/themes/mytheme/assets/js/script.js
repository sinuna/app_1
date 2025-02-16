document.addEventListener('DOMContentLoaded', function () {
    const taskApp = Vue.createApp({
        data() {
            return {
                isClient: true,
                restUrl: ajax_object.rest_url,
                allTaxonomies: [],
            };
        },
        mounted() {
            this.fetchAllTaxonomies();
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
            }
        }
    });

    taskApp.mount('#task-app');
});
