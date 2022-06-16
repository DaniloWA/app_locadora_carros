<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- início do card de busca -->
                <card-component titulo="Busca de modelos">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do modelo">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome do modelo" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome do modelo">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome do modelo" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->

                <!-- início do card de listagem de modelo -->
                <card-component titulo="Relação de modelos">
                    <template v-slot:conteudo>
                        <table-component
                        :dados="modelo"
                        :visualizar="{
                            visivel: true,
                            dataToggle: 'modal',
                            dataTarget: '#modalModeloVisualizar'
                        }"
                        :atualizar="{
                            visivel: true,
                              dataToggle: 'modal',
                              dataTarget: '#modalModeloAtualizar'
                        }"
                        :remover="{
                            visivel: true,
                            dataToggle: 'modal',
                            dataTarget: '#modalModeloRemover'
                        }"
                        :titulos="{
                            id: {titulo: 'ID', tipo: 'text'},
                            nome:{titulo: 'Nome', tipo: 'text'},
                            air_bag:{titulo: 'Airbag', tipo: 'boolean'},
                            abs:{titulo: 'ABS', tipo: 'boolean'},
                            numero_portas:{titulo: 'N/portas', tipo: 'off'},
                            lugares:{titulo: 'N/lugares', tipo: 'off'},
                            marca:{titulo: 'marca', tipo: 'off'},
                            imagem:{titulo: 'Imagem', tipo: 'imagem'},
                            created_at:{titulo: 'Data de criação', tipo: 'data'}
                        }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in modelo.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label" ></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalModelo" @click="carregarMarcas()">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de modelo -->
            </div>
        </div>



        <!-- inicio modal de inclusão de modelo -->
        <modal-component id="modalModelo" titulo="Adicionar modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso!"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o modelo" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Marca do modelo" id="atualizarMarca" id-help="atualizarMarcaHelp" texto-ajuda="Informe a marca do modelo">
                        <select class="form-control" id="atualizarMarca" aria-describedby="atualizarMarcaHelp" placeholder="Marca do modelo" aria-placeholder="Selecione uma marca" v-model="selectMarca">
                            <option value="" disabled selected hidden>Marca do modelo</option>
                            <option v-for="data, key in dataMarcas" :key="key" :value="data.id">{{ data.nome }}</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Nome do modelo" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o nome do modelo">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome do modelo" v-model="nomeModelo">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Numero de portas" id="atualizarPortas" id-help="atualizarPortasHelp" texto-ajuda="Informe o numero de portas">
                        <input type="number" class="form-control" id="atualizarPortas" aria-describedby="atualizarPortasHelp" placeholder="Numero de portas" v-model="numeroPortas">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Numero de lugares" id="atualizarLugares" id-help="atualizarLugaresHelp" texto-ajuda="Informe o numero de lugares">
                        <input type="number" class="form-control" id="atualizarLugares" aria-describedby="atualizarLugaresHelp" placeholder="Numero de lugares" v-model="numeroLugares">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Airbag" id="atualizarAirbag" id-help="atualizarAirbagHelp" texto-ajuda="Informe se tem Airbag">
                        <input type="checkbox" class="form-control" id="atualizarAirbag" aria-describedby="atualizarAirbagHelp" placeholder="Airbag" v-model="Airbag">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="ABS" id="atualizarABS" id-help="atualizarABSHelp" texto-ajuda="Informe se tem ABS">
                        <input type="checkbox" class="form-control" id="atualizarABS" aria-describedby="atualizarABSHelp" placeholder="ABS" v-model="ABS">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- final modal de inclusão de modelo -->



        <!-- inicio modal de visualização de modelo -->

        <modal-component id="modalModeloVisualizar" titulo="Visualizar modelo">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>

                <input-container-component titulo="Imagem">
                    <img v-if="$store.state.item.imagem" :src="'storage/' + $store.state.item.imagem" alt="img">
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>

        </modal-component>

        <!-- final modal de visualização de modelo -->



        <!-- inicio modal de remoção de modelo -->

        <modal-component id="modalModeloRemover" titulo="Remover modelo">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Nome">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()"  v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>

        <!-- final modal de remoção de modelo -->



        <!-- inicio modal de atualização de modelo de modelo -->
        <modal-component id="modalModeloAtualizar" titulo="Atualizar modelo">

            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome do modelo" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome do modelo">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome do modelo" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>
        </modal-component>
        <!-- final modal de atualização de modelo de modelo -->

    </div>
</template>

<script>

export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPaginacao: '',
                urlFiltro: '',
                dataMarcas: '',
                dataMarcasModal:'',
                selectMarca: '',
                nomeModelo: '',
                numeroPortas: '',
                numeroLugares: '',
                Airbag: false,
                ABS: false,
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                modelo: { data:[] },
                busca: { id:'', nome:'' }
            }
        },
        computed: {
            checkAirbag(){
               if(this.Airbag == true ) return this.Airbag = 1
               if(this.Airbag == false ) return this.Airbag = 0
            },
            checkABS(){
               if(this.ABS == true ) return this.ABS = 1
               if(this.ABS == false ) return this.ABS = 0
            },


        },
        methods: {

            atualizar(){

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)

                if(this.arquivoImagem[0]) {
                    formData.append('imagem', this.arquivoImagem[0])
                }


                let url = this.urlBase + '/' + this.$store.state.item.id
                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(url, formData, config)
                    .then(response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Registro de modelo atualizado com sucesso!'

                        //limpando o campo de seleção de arquivos pelo ID
                        novoImagem.value = ''
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                    })

            },

            remover() {
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')

                if(!confirmacao) return false

                let formData = new FormData()
                formData.append('_method', 'delete')

                let url = this.urlBase + '/' + this.$store.state.item.id


                axios.post(url, formData)
                    .then(response=>{
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.erro
                    })
            },

            pesquisar(){
                //console.log(this.busca)
                let filtro = ''

                for(let chave in this.busca) {
                    if(this.busca[chave]){
                        if(filtro != ''){
                        filtro +=';'
                    }
                    filtro += chave +':like:' + this.busca[chave]
                    }
                }
                if(filtro != ''){
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro=' + filtro + '%'
                } else {
                    this.urlFiltro = ''
                }
                this.carregarLista()

            },

            carregarMarcas(){
                let url = "http://localhost:8000/api/v1/marca/data/all"

                axios.get(url)
                    .then(response => {
                        this.dataMarcas = response.data
                        console.log(this.dataMarcas)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },

            carregarLista(){

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                axios.get(url)
                    .then(response => {
                        this.modelo = response.data
                        console.log(this.modelo)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },

            carregarImagem(e) {
                this.arquivoImagem = e.target.files
            },
            salvar() {
                let formData = new FormData();
                formData.append('marca_id', this.selectMarca)
                formData.append('nome', this.nomeModelo)
                formData.append('numero_portas', this.numeroPortas)
                formData.append('lugares', this.numeroLugares)
                formData.append('air_bag', this.checkAirbag)
                formData.append('abs', this.checkABS)
                formData.append('imagem', this.arquivoImagem[0])

                let config = {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                }

                axios.post(this.urlBase, formData, config)
                    .then(response => {
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: ' + response.data.id
                        }
                        this.carregarLista()
                    })
                    .catch(errors => {
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes = {
                            mensagem: errors.response.data.message,
                            dados: errors.response.data.errors
                        }
                        //console.log(errors.response.data)
                    })
            }
        },

        mounted(){
            this.carregarLista()
        }
    }
</script>
