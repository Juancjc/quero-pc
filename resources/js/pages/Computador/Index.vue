<template>
    <Head title="Computador" />
    <div
        class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
    >
        <Heading
            title="Listagem de Computadores"
            description="Computadores cadastrados no sistema"
        />
        <Button as-child variant="outline" class="w-fit">
            <Link :href="create()">Adicionar Computador</Link>
        </Button>
        <div
            v-if="dados.length"
            class="grid auto-rows-min gap-4 md:grid-cols-3"
        >
            <Card v-for="computador in dados" :key="computador.id">
                <CardHeader>
                    <CardTitle>{{ computador.nome }}</CardTitle>
                    <CardDescription>{{
                        computador.user?.name
                    }}</CardDescription>
                    <CardAction>
                        <Badge :variant="statusVariant(computador.status)">
                            {{ computador.status }}
                        </Badge>
                    </CardAction>
                </CardHeader>
                <CardContent class="flex flex-col gap-3">
                    <p class="text-sm text-muted-foreground">
                        {{ computador.descricao }}
                    </p>
                    <Button as-child size="sm" variant="outline" class="w-fit">
                        <Link :href="edit(computador.id)">Editar</Link>
                    </Button>
                    <Button
                        size="sm"
                        variant="outline"
                        class="w-fit"
                        @click="excluir(computador.id)"
                    >
                        Excluir
                    </Button>
                </CardContent>
            </Card>
        </div>

        <div
            v-else
            class="relative flex min-h-[50vh] flex-1 items-center justify-center rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
        >
            <p class="text-sm text-muted-foreground">
                Nenhum computador cadastrado.
            </p>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import type { BadgeVariants } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { create, deleteMethod, edit } from '@/routes/computador';

type Computador = {
    id: number;
    nome: string;
    descricao: string;
    status: string;
    user?: {
        name: string;
    };
};

defineProps<{
    dados: Computador[];
}>();

function statusVariant(status: string): BadgeVariants['variant'] {
    switch (status) {
        case 'Ativo':
            return 'default';
        case 'Comprado':
            return 'secondary';
        case 'Inativo':
            return 'outline';
        case 'Esperando Milagre':
            return 'destructive';
        default:
            return 'outline';
    }
}
function excluir(id: number) {
    if (!confirm('Deseja realmente excluir este computador?')) {
        return;
    }

    axios.delete(deleteMethod.url(id)).then(() => router.reload());
}
</script>
