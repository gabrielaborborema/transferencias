# Arquitetura do Projeto

Esse projeto utiliza a TALL stack (Tailwind, AlpineJS, Laravel e Livewire), favorecendo o uso de componentes server-side reativos para construção da interface.

## Estrutura de Pastas Relevantes

-   `app/Livewire/`: Contém as classes dos componentes Livewire, que atuam como a lógica da UI.
-   `app/Services/`: Contém a lógica das principais regras de negócio da aplicação
-   `app/Jobs`: Contém os Jobs que são despachados para a fila.
-   `resources/views/livewire/`: Contém os arquivos Blade para cada componente Livewire.

## Conceitos Chave

-   **Componentes Livewire como Controladores:** A lógica de interação com o usuário é gerenciada pelos componentes Livewire em `app/Livewire/`.
-   **Regras de Negócio:** Para manter os componentes limpos e ter uma estrutura bem definida, a lógica de negócios crítica foi delegada para os services em `app/Services/`.
