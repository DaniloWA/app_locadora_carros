<template>
    <div>
        <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"  v-for="t, key in titulos" :key="key">{{t}}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="marcas in dados" :key="marcas.id">
                <th scope="row">{{marcas.id}}</th>
                <td>{{marcas.nome}}</td>
                <td><img :src="'/storage/' + marcas.imagem" width="30" height="30" alt="img"></td>
            </tr>

        </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos']
    }
</script>
