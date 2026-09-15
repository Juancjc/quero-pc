<template>
    <div class="flex max-w-3xl flex-col gap-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="flex flex-col gap-1.5">
                <Label for="nome">Nome do Computador</Label>
                <Input
                    id="nome"
                    v-model="form.nome"
                    placeholder="Ex: PC Gamer"
                />
                <InputError :message="form.errors.nome" />
            </div>

            <div class="flex flex-col gap-1.5">
                <Label for="status">Status</Label>
                <Select v-model="form.status">
                    <SelectTrigger id="status" class="w-full">
                        <SelectValue placeholder="Selecione o status" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="opcao in statusDisponiveis"
                            :key="opcao"
                            :value="opcao"
                        >
                            {{ opcao }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.status" />
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <Label for="descricao">Descrição</Label>
            <Input
                id="descricao"
                v-model="form.descricao"
                placeholder="Descrição do computador"
            />
            <InputError :message="form.errors.descricao" />
        </div>

        <div class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium" v-if="form.nome">Peças Desejadas</h3>
                <Button
                    type="button"
                    size="sm"
                    variant="outline"
                    @click="adicionarPeca"
                    :disabled="!form.nome"
                >
                    Adicionar peça
                </Button>
            </div>

            <p
                v-if="!form.pecas_desejadas.length"
                class="text-sm text-muted-foreground"
            >
                Nenhuma peça adicionada. Clique em "Adicionar peça" para
                começar.
            </p>

            <div
                v-for="(peca, index) in form.pecas_desejadas"
                :key="index"
                class="flex flex-col gap-3 rounded-lg border p-4"
            >
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="flex flex-col gap-1.5">
                        <Label>Tipo de componente</Label>
                        <Select v-model="peca.componente_computadore_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Selecione o tipo" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="componente in componentes"
                                    :key="componente.id"
                                    :value="String(componente.id)"
                                >
                                    {{ componente.nome }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError
                            :message="
                                erro(
                                    `pecas_desejadas.${index}.componente_computadore_id`,
                                )
                            "
                        />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label>Peça desejada</Label>
                        <PecaComboBox
                            v-model="peca.descricao"
                            v-model:api-pc-id="peca.api_pc_id"
                        />
                        <InputError
                            :message="
                                erro(`pecas_desejadas.${index}.descricao`)
                            "
                        />
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="flex flex-col gap-1.5 sm:col-span-2">
                        <Label>Link onde encontrou</Label>
                        <Input
                            v-model="peca.link_inicial"
                            placeholder="https://..."
                        />
                        <InputError
                            :message="
                                erro(`pecas_desejadas.${index}.link_inicial`)
                            "
                        />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <Label>Valor encontrado (R$)</Label>
                        <Input
                            v-model="peca.valor_inicial"
                            type="number"
                            min="0"
                            step="0.01"
                            placeholder="0,00"
                        />
                        <InputError
                            :message="
                                erro(`pecas_desejadas.${index}.valor_inicial`)
                            "
                        />
                    </div>
                </div>

                <div class="flex items-end justify-between gap-3">
                    <div class="flex flex-col gap-1.5">
                        <Label>Quantidade</Label>
                        <Input
                            v-model="peca.quantidade"
                            type="number"
                            min="1"
                            class="w-24"
                        />
                        <InputError
                            :message="
                                erro(`pecas_desejadas.${index}.quantidade`)
                            "
                        />
                    </div>

                    <Button
                        type="button"
                        size="sm"
                        variant="ghost"
                        @click="removerPeca(index)"
                    >
                        Remover
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { computed } from 'vue';
import PecaComboBox from '@/components/Computador/PecaComboBox.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

type Componente = {
    id: number;
    nome: string;
};

const props = defineProps<{
    modelValue: Record<string, any>;
    componentes: Componente[];
}>();

const emit = defineEmits(['update:modelValue']);

const statusDisponiveis = ['Ativo', 'Inativo', 'Comprado', 'Esperando Milagre'];

const form = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});

function erro(campo: string) {
    return form.value.errors?.[campo];
}

function adicionarPeca() {
    form.value.pecas_desejadas.push({
        id: null,
        componente_computadore_id: '',
        descricao: '',
        api_pc_id: null,
        quantidade: 1,
        link_inicial: '',
        valor_inicial: '',
    });
}

function removerPeca(index: number) {
    form.value.pecas_desejadas.splice(index, 1);
}
</script>
