<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- início do card de busca -->
                <card-component titulo="Busca de carros">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp" texto-ajuda="Opcional. Informe o ID do carro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome do carro" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional. Informe o nome do carro">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome do carro" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->

                <!-- início do card de listagem de carro -->
                <card-component titulo="Relação de carros">
                    <template v-slot:conteudo>
                        <table-component
                        :dados="carro"
                        :visualizar="{
                            visivel: true,
                            dataToggle: 'modal',
                            dataTarget: '#modalCarroVisualizar'
                        }"
                        :atualizar="{
                            visivel: true,
                              dataToggle: 'modal',
                              dataTarget: '#modalCarroAtualizar'
                        }"
                        :remover="{
                            visivel: true,
                            dataToggle: 'modal',
                            dataTarget: '#modalCarroRemover'
                        }"
                        :titulos="{
                            id:{titulo: 'ID', tipo: 'text'},
                            placa:{titulo: 'Placa', tipo: 'text'},
                            modelo_id:{titulo: 'marca', tipo: 'off'},
                            disponivel:{titulo: 'Disponivel', tipo: 'boolean'},
                            km:{titulo: 'KM', tipo: 'text'},
                            created_at:{titulo: 'Data de criação', tipo: 'data'}
                        }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="l, key in carro.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                        <a class="page-link" v-html="l.label" ></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCarro">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de listagem de carro -->
            </div>
        </div>



        <!-- inicio modal de inclusão de carro -->
        <modal-component id="modalCarro" titulo="Adicionar carro">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso!"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o carro" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Modelo do carro" id="atualizarModelo" id-help="atualizarModeloHelp" texto-ajuda="Informe a marca do carro">
                        <select class="form-control" id="atualizarModelo" aria-describedby="atualizarModeloHelp" placeholder="Modelo do carro" aria-placeholder="Selecione uma marca" v-model="selectModelo">
                            <option value="" disabled selected hidden>Modelo do carro</option>
                            <option v-for="data, key in dataModelo" :key="key" :value="data.id">{{ data.nome }}</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Placa do carro" id="atualizarPlaca" id-help="atualizarPlacaHelp" texto-ajuda="Informe a Placa do carro">
                        <input type="text" class="form-control" id="atualizarPlaca" aria-describedby="atualizarPlacaHelp" placeholder="Placa do carro" v-model="placaCarro">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quilometro do carro" id="atualizarQuilometro" id-help="atualizarQuilometroHelp" texto-ajuda="Informe a Quilometragem do carro">
                        <input type="number" class="form-control" id="atualizarQuilometro" aria-describedby="atualizarQuilometroHelp" placeholder="Quilometro do carro" v-model="km">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Disponivel ?" id="atualizarDisponivel" id-help="atualizarDisponivelHelp" texto-ajuda="Informe se está Disponivel">
                        <input type="checkbox" class="form-control" id="atualizarDisponivel" aria-describedby="atualizarDisponivelHelp" placeholder="Disponivel" v-model="disponivel">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- final modal de inclusão de carro -->



        <!-- inicio modal de visualização de carro -->

        <modal-component id="modalCarroVisualizar" titulo="Visualizar carro">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Placa">
                    <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                </input-container-component>

                <input-container-component titulo="KM">
                    <input type="text" class="form-control" :value="$store.state.item.km" disabled>
                </input-container-component>

                <input-container-component titulo="Disponivel">
                    <input type="text" class="form-control" :value="$store.state.item.disponivel" disabled>
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </template>

        </modal-component>

        <!-- final modal de visualização de carro -->



        <!-- inicio modal de remoção de carro -->

        <modal-component id="modalCarroRemover" titulo="Remover carro">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component titulo="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>

                <input-container-component titulo="Placa">
                    <input type="text" class="form-control" :value="$store.state.item.placa" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()"  v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>

        <!-- final modal de remoção de carro -->



        <!-- inicio modal de atualização de carro de carro -->
        <modal-component id="modalCarroAtualizar" titulo="Atualizar carro">
            <template v-slot:alertas>
                <alert-component tipo="success" v-if="transacaoStatus == 'adicionado'" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com sucesso!"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar o carro" v-if="transacaoStatus == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                {{ $store.state.item.disponivel }} -- Disponivel
                <div class="form-group">
                    <input-container-component titulo="Modelo do carro" id="atualizarModelo" id-help="atualizarModeloHelp" texto-ajuda="Informe a marca do carro">
                        <select class="form-control" id="atualizarModelo" aria-describedby="atualizarModeloHelp" placeholder="Modelo do carro" aria-placeholder="Selecione uma marca" v-model="selectModelo">
                            <option value="" disabled selected hidden>Modelo do carro</option>
                            <option v-for="data, key in dataModelo" :key="key" :value="data.id">{{ data.nome }}</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Placa do carro" id="atualizarPlaca" id-help="atualizarPlacaHelp" texto-ajuda="Informe a Placa do carro">
                        <input type="text" class="form-control" id="atualizarPlaca" aria-describedby="atualizarPlacaHelp" placeholder="Placa do carro" v-model="$store.state.item.placa">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Quilometro do carro" id="atualizarQuilometro" id-help="atualizarQuilometroHelp" texto-ajuda="Informe a Quilometragem do carro">
                        <input type="number" class="form-control" id="atualizarQuilometro" aria-describedby="atualizarQuilometroHelp" placeholder="Quilometro do carro" v-model="$store.state.item.km">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Disponivel ?" id="atualizarDisponivel" id-help="atualizarDisponivelHelp" texto-ajuda="Informe se está Disponivel">
                        <input type="checkbox" class="form-control" id="atualizarDisponivel" aria-describedby="atualizarDisponivelHelp" placeholder="Disponivel" v-model="$store.state.item.disponivel">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Salvar</button>
            </template>
        </modal-component>
        <!-- final modal de atualização de carro de carro -->

    </div>
</template>

<script>

export default {
        data() {
            return {
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlPaginacao: '',
                urlFiltro: '',
                dataModelo: '',
                dataMarcasModal:'',
                selectModelo: '',
                placaCarro: '',
                km: '',
                numeroLugares: '',
                disponivel: false,
                arquivoImagem: [],
                transacaoStatus: '',
                transacaoDetalhes: {},
                carro: [],
                busca: { id:'', nome:'' }
            }
        },
        computed: {
            checkDisponivel(){
               if(this.disponivel == true ) return this.disponivel = 1
               if(this.disponivel == false ) return this.disponivel = 0
            }
        },
        methods: {
            atualizar(){

                let formData = new FormData()
                formData.append('_method', 'patch')
                formData.append('marca_id', this.$store.state.item.marca.id)
                formData.append('nome', this.$store.state.item.nome)
                formData.append('numero_portas', this.$store.state.item.numero_portas)
                formData.append('lugares', this.$store.state.item.lugares)
                formData.append('air_bag', this.$store.state.item.air_bag)
                formData.append('abs', this.$store.state.item.abs)

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
                        console.log(response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'Registro de carro atualizado com sucesso!'
                        atualizarImagem.value = ''
                        this.carregarLista()
                    })
                    .catch(errors => {
                        console.log(errors)
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
                let url = "http://localhost:8000/api/v1/modelo"

                axios.get(url)
                    .then(response => {
                        this.dataModelo = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },

            carregarLista(){

                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro

                axios.get(url)
                    .then(response => {
                        console.log(response.data)
                        this.carro = response.data
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
                formData.append('modelo_id', this.selectModelo)
                formData.append('placa', this.placaCarro)
                formData.append('disponivel', this.checkDisponivel)
                formData.append('km', this.km)

                let config = {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
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
                    })
            }
        },

        mounted(){
            this.carregarLista()
            this.carregarMarcas()
        }
    }
</script>
