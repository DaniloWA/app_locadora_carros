<template>
    <div>
        <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"  v-for="t, key in titulos" :key="key">{{t.titulo}}</th>
                <th v-if="visualizar || atualizar || remover"></th>
            </tr>
        </thead>
        <tbody>

            <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                <td v-for="valor, chaveValor in obj" :key="chaveValor">
                    <span v-if="titulos[chaveValor].tipo == 'text'">{{ valor }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'data'">{{ '...'+valor }}</span>
                    <span v-if="titulos[chaveValor].tipo == 'imagem'">
                         <img :src="'/storage/' + valor" width="30" height="30" alt="img">
                    </span>
                </td>
                <td v-if="visualizar || atualizar || remover">
                     <button v-if="visualizar" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modalMarcaVisualizar">Visualziar</button>
                     <button v-if="atualizar"  class="btn btn-outline-primary btn-sm">Atualizar</button>
                     <button v-if="remover"  class="btn btn-outline-danger btn-sm">Remover</button>
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
