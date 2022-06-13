<template>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Bem-vindo  -  {{ userName }}</div>

                <div class="card-body">
                    Marcas registradas: {{ this.marcasData.total }} <br>
                    Modelos registrados: {{ this.modeloData.length }} <br>
                    Carros registrados: {{ this.carrosData.length }} <br>
                    Clientes registrados: {{ this.clientesData.length }}
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        props: ['userName', 'userMail'],
        data() {
            return{
                marcasData: '',
                modeloData: '',
                carrosData: '',
                clientesData: ''
            }
        },
        methods: {
            carregarDados(){

                axios.get('http://localhost:8000/api/v1/marca')
                    .then(response => {
                        this.marcasData = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })

                axios.get('http://localhost:8000/api/v1/modelo')
                    .then(response => {
                        this.modeloData = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })

                axios.get('http://localhost:8000/api/v1/carro')
                    .then(response => {
                        this.carrosData = response.data
                        console.log(response.data)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })

                axios.get('http://localhost:8000/api/v1/cliente')
                    .then(response => {
                        this.clientesData = response.data
                        console.log(response.data)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
        },
        mounted(){
            this.carregarDados()
        }
    }
</script>
