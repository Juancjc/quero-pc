<template>
    <div class="relative">
        <Input
            v-model="busca"
            type="text"
            autocomplete="off"
            placeholder="Pesquisar peça no catálogo..."
            @input="aoDigitar"
            @focus="aberto = true"
            @blur="fecharComAtraso"
        />
        <ul
            v-if="aberto && (resultados.length > 0 || carregando)"
            class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md border bg-popover text-popover-foreground shadow-md"
        >
            <li
                v-if="carregando"
                class="px-3 py-2 text-sm text-muted-foreground"
            >
                Buscando...
            </li>
            <li
                v-for="peca in resultados"
                :key="peca.id"
                class="cursor-pointer px-3 py-2 text-sm hover:bg-accent hover:text-accent-foreground"
                @mousedown.prevent="selecionar(peca)"
            >
                <p class="font-medium">{{ peca.name }}</p>
                <p
                    v-if="peca.manufacturer || peca.model"
                    class="text-xs text-muted-foreground"
                >
                    {{
                        [peca.manufacturer, peca.model]
                            .filter(Boolean)
                            .join(' · ')
                    }}
                </p>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import { buscar } from '@/routes/computador/pecas';

type PecaCatalogo = {
    id: string;
    name: string;
    manufacturer?: string;
    model?: string;
};

const props = defineProps<{
    modelValue: string;
    apiPcId?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'update:apiPcId', value: string | null): void;
}>();

const busca = ref(props.modelValue ?? '');
const resultados = ref<PecaCatalogo[]>([]);
const carregando = ref(false);
const aberto = ref(false);
let temporizador: ReturnType<typeof setTimeout> | undefined;

watch(
    () => props.modelValue,
    (valor) => {
        busca.value = valor ?? '';
    },
);

function aoDigitar() {
    emit('update:modelValue', busca.value);
    emit('update:apiPcId', null);
    aberto.value = true;
    clearTimeout(temporizador);

    if (busca.value.trim().length < 2) {
        resultados.value = [];

        return;
    }

    temporizador = setTimeout(buscarNoCatalogo, 300);
}

async function buscarNoCatalogo() {
    carregando.value = true;

    try {
        const resposta = await fetch(
            buscar.url({ query: { busca: busca.value } }),
        );
        const json = await resposta.json();
        resultados.value = json.data ?? [];
    } catch {
        resultados.value = [];
    } finally {
        carregando.value = false;
    }
}

function fecharComAtraso() {
    // atraso para o clique no item (mousedown) acontecer antes do blur fechar a lista
    setTimeout(() => (aberto.value = false), 150);
}

function selecionar(peca: PecaCatalogo) {
    busca.value = peca.name;
    emit('update:modelValue', peca.name);
    emit('update:apiPcId', peca.id);
    resultados.value = [];
    aberto.value = false;
}
</script>
