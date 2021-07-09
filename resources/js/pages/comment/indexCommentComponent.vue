<template>
    <div class="padding container-fluid">
        <div class="row">
            <div class="col m12 s12 mt">
                <h3 class="center-align"> Administración de comentarios ! </h3>
                <div v-if="comments.length > 0">
                    <md-table class="mt mb">
                        <md-table-row>
                            <md-table-head md-numeric>ID</md-table-head>
                            <md-table-head>Fecha</md-table-head>
                            <md-table-head>Hora</md-table-head>
                            <md-table-head>Comentario</md-table-head>
                            <md-table-head>Status</md-table-head>
                            <md-table-head>Correo</md-table-head>
                            <md-table-head>Post</md-table-head>
                            <md-table-head>Acciones</md-table-head>
                        </md-table-row>
                        <md-table-row v-for="(comment, index) in comments" :key="index">
                            <md-table-cell md-numeric>{{ index + 1 }}</md-table-cell>
                            <md-table-cell>{{ comment.created_at.slice(0, 10) }}</md-table-cell>
                            <md-table-cell>{{ comment.created_at.slice(11, 19) }}</md-table-cell>
                            <md-table-cell>{{ comment.content }}</md-table-cell>
                            <md-table-cell>{{ comment.status }}</md-table-cell>
                            <md-table-cell>{{ JSON.parse(comment.participant_data).email }}</md-table-cell>
                            <md-table-cell>{{ comment.post.title }}</md-table-cell>
                            <md-table-cell v-if="comment.status == 'revision'">
                                <button class="btn btn-small green" @click="approvedComment(comment.id, comment.content)">Aprobar</button>
                                <button class="btn btn-small red" @click="deneidComment(comment.id)">Negar</button>
                            </md-table-cell>
                            <md-table-cell v-else>
                                <button 
                                    :class="[comment.status == 'approved' ? 'btn btn-small green disabled' : 'btn btn-small red disabled']" 
                                    v-text="comment.status == 'approved' ? 'Aprobado' : 'Negado'"
                                ></button>
                            </md-table-cell>
                        </md-table-row>
                    </md-table>
                </div>
                <h4 class="text-center" v-else>No hay información para mostrar</h4>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2"
export default {
    data() {
        return {
            comments: []
        }
    },
    methods: {
        async getAllComments() {
            try {
                const url       = `/admin/comments/all`
                let responses   = await axios.get(url)

                if(responses.data) {
                    this.comments = responses.data
                }
            } catch (error) {
                console.log(error)
            }
        },
        async deneidComment(id) {
            try {
                const url   = `/admin/comments/deneid`
                let params  = {
                    id
                }

                let responses = await axios.post(url, params)

                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Comentario denegado correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getAllComments()
                }
            } catch (error) {
                console.log(error)
            }
        },
        async setAnswerComment(content, comment_id) {
            try {
                const url   = `/admin/answer/approved`
                let params  = {
                    content,
                    comment_id
                }

                let responses = await axios.post(url, params)

                if(responses.data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Comentario respondido correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getAllComments()
                }
            } catch (error) {
                console.log(error)
            }
        },
        async approvedComment(comment_id, content) {
            const { value: response } = await Swal.fire({
                title: 'Responder comentario !',
                html: `<p>${content}</p>`,
                input: 'text',
                inputLabel: 'Su respuesta: ',
                inputPlaceholder: 'Ingrese una respuesta aquí'
            })
            if (response) {
                Swal.fire(`Ingresaste esta respuesta: ${response}`)
                this.setAnswerComment(response, comment_id)
            }
        },
    },
    mounted() {
        this.getAllComments()
    }
}
</script>