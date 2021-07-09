<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> ¡ Estadísticas ! </h3>
                <div v-if="questions.length > 0">
                    <md-table class="mt mb">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Fecha</md-table-head>
                            <md-table-head>Hora</md-table-head>
                            <md-table-head>Comentario</md-table-head>
                            <md-table-head>Status</md-table-head>
                            <md-table-head>Correo</md-table-head>
                            <md-table-head>Event</md-table-head>
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
                <h4 class="text-center" v-else>No hay información para mostrar</h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            comments: []
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
        }
    },
    mounted() {
        this.getAllComment()
    }
}
</script>