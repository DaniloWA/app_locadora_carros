<template>
    <div>
        <table class="table table-hover">
        <thead>
            <tr>
                <template v-for="t, key in titulos">
                    <th scope="col"  v-if="confirmTitle(t.titulo)" :key="key">{{ t.titulo }}</th>
                </template>
                <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                <template v-for="valor, chaveValor in obj">
                    <td v-if="titulos[chaveValor].tipo != 'off'" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'boolean'">{{ valor == 1 ? "sim" : "não" }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ valor | formatadaDataTempo }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30" alt="img">
                        </span>
                    </td>
                </template>
                <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                     <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualziar</button>
                     <button v-if="atualizar.visivel"  class="btn btn-outline-primary btn-sm" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(obj)">Atualizar</button>
                     <button v-if="remover.visivel"  class="btn btn-outline-danger btn-sm" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj)">Remover</button>
                </td>
            </tr>

          <!--
            <tr v-for="obj in dados" :key="obj.id">
                <template v-for="valor, chave in obj">
                    <td :key="chave" v-if="titulos.includes(chave)">
                        <span v-if="chave == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30" alt="img">
                        </span>
                        <span v-else>
                            {{valor}}
                        </span>
                    </td>
                </template>


                <th scope="row">{{marcas.id}}</th>
                <td>{{marcas.nome}}</td>
                <td><img :src="'/storage/' + marcas.imagem" width="30" height="30" alt="img"></td>
            </tr>
        -->
        </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'atualizar','visualizar','remover'],
        methods: {
            setStore(obj) {
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
            },
            confirmTitle(titulo){
                if(titulo == 'N/portas' || titulo == 'marca' || titulo == 'N/lugares'){
                    return false
                } else {
                    return true
                }
            }
        },
        computed: {
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                const dadosFiltrados = []
                this.dados.map((item, chave) => {

                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo] // utilizar a sintaxe de array para atribuir valores a objetos

                    })
                    dadosFiltrados.push(itemFiltrado)
                })
                return dadosFiltrados
            }
        }
    }
</script>
