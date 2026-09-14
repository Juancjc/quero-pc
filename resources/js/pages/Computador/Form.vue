<template>
    <Head title="Formulário de Computador" />
    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            title="Formulário de Computador"
            description="Preencha os dados do computador"
        />

        <ComputadorFormulario v-model="form" :componentes="componentes" />

        <div class="flex max-w-xl justify-end gap-2">
            <Button as-child variant="outline">
                <Link :href="index()">Voltar</Link>
            </Button>
            <Button :disabled="form.processing" @click="salvar">
                {{ dados ? 'Atualizar' : 'Cadastrar' }}
            </Button>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ComputadorFormulario from '@/components/Computador/Formulario.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { index, store, update } from '@/routes/computador';

type PecaDesejada = {
    id: number;
    componente_computadore_id: number;
    descricao: string;
    api_pc_id: string | null;
    quantidade: number;
    link_inicial: string;
    valor_inicial: string;
};

type Computador = {
    id: number;
    nome: string;
    descricao: string;
    status: string;
    pecas_desejadas: PecaDesejada[];
};

const props = defineProps<{
    dados: Computador | null;
    componentes: Array<{ id: number; nome: string }>;
}>();

const form = useForm({
    nome: props.dados?.nome ?? '',
    descricao: props.dados?.descricao ?? '',
    status: props.dados?.status ?? 'Ativo',
    pecas_desejadas: (props.dados?.pecas_desejadas ?? []).map((peca) => ({
        id: peca.id,
        componente_computadore_id: String(peca.componente_computadore_id),
        descricao: peca.descricao,
        api_pc_id: peca.api_pc_id,
        quantidade: peca.quantidade,
        link_inicial: peca.link_inicial,
        valor_inicial: peca.valor_inicial,
    })),
});

function salvar() {
    if (props.dados) {
        form.put(update.url(props.dados.id));
    } else {
        form.post(store.url());
    }
}
</script>
<style scoped></style>
