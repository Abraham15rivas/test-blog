<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> ¡ Estadísticas ! </h3>
                <div class="card">
                    <div class="card-body">
                        <highcharts :options="chartOptions"></highcharts>
                    </div>
                </div>
                <span>
                    <div v-if="comments.length > 0">
                        <h3 class="center-align">Comentarios denegados</h3>
                        <md-table class="mt mb">
                            <md-table-row>
                                <md-table-head md-numeric>ID</md-table-head>
                                <md-table-head>Fecha</md-table-head>
                                <md-table-head>Hora</md-table-head>
                                <md-table-head>Comentario</md-table-head>
                                <md-table-head>Status</md-table-head>
                                <md-table-head>Correo</md-table-head>
                                <md-table-head>Post</md-table-head>
                            </md-table-row>
                            <md-table-row v-for="(comment, index) in comments" :key="index">
                                <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                                <md-table-cell>{{ comment.created_at.slice(0, 10) }}</md-table-cell>
                                <md-table-cell>{{ comment.created_at.slice(11, 19) }}</md-table-cell>
                                <md-table-cell>{{ comment.content }}</md-table-cell>
                                <md-table-cell>{{ comment.status }}</md-table-cell>
                                <md-table-cell>{{ JSON.parse(comment.participant_data).email }}</md-table-cell>
                                <md-table-cell>{{ comment.post.title }}</md-table-cell>
                            </md-table-row>
                        </md-table>
                    </div>
                    <h4 class="text-center" v-else>No hay información para mostrar en la tabla</h4>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            comments: [],
            options: ['spline', 'line', 'bar', 'pie', 'column'],
            posts: []
        }
    },
    methods: {
        async getAllComment() {
            try {
                const url       = `/admin/comments/deneid`
                let responses   = await axios.get(url)
                if(responses.data) {
                    this.comments = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async visits() {
            try {
                const url       = `/admin/all/visits`
                let response    = await axios.get(url)

                if (response.data) {
                    this.posts = response.data
                }
            } catch (error) {
                console.log(error)
            }
        }
    },
    computed: {
        chartOptions() { 
            return {
                chart: { 
                    type: 'pie' 
                },
                title: { 
                    text: `Total de post: ${ this.posts.length }` 
                },
                series: [{
                    name: 'Vistas',
                    colorByPoint: true,
                    data: this.posts
                }]
            }
        },
    },
    mounted() {
        this.getAllComment()
        this.visits()
    }
}
</script>