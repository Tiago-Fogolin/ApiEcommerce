# 1. Modelagem de Banco de Dados


- Foi criado um Banco de Dados para o cenário de e-commerce com as tabelas:
Clientes, Produtos, Categorias, Pedidos, Produto_Pedido, Produto_Categoria, Tipos_Pagamento e Pagamentos:

![Imagem do Banco](https://github.com/Tiago-Fogolin/ApiEcommerce/blob/master/db.png)

- Essa modelagem está disponível para melhor visualização no site: https://app.brmodeloweb.com/#!/publicview/6751703eec7ab57db648c01b

- Para cada tabela foi criado como chave primária um campo de ID, que geralmente são utilizados, já que otimizam a performance de consultas e garantem estabilidade em relacionamentos entre tabelas.

- Essas são as chaves estrangeiras, e o motivo da escolha de cada uma:
    - A tabela Pedidos a FK id_cliente, para que seja possível vincular um pedido a um cliente.
    - A tabela Produto_Pedido possui como FK as colunas id_pedido e id_produto, para que seja possível vincular produtos a um pedido.
    - A tabela Produto_Categoria possui como FK as colunas id_categoria e id_produto, para que seja possível vincular categorias a um pedido.
    - A tabela Pagamentos possui como FK as colunas id_pedido e id_tipo_pagamento, para que seja possível registrar um pagamento, com um tipo específico de pagamento, vinculado a um pedido.

- Em um primeiro momento para esse banco de dados, não foram criados índices extras, além das chaves primárias e chaves estrangeiras, porque somente com elas já seriam atendidos um bom desempenho. Conforme a aplicação fosse crescendo e necessidades surgindo, poderia ser avaliado a criação de outros índices, para que melhorassem o desempenho das consultas.

- E para a normalização, foram criadas algumas tabelas para permitir múltiplos relacionamentos:
    - Tabela Produto_Pedido, foi criada para que fosse possível vincular mais de um pedido para um produto.
    - Tabela Produto_Categoria, também foi criada para suportar múltiplos relacionamentos, mas nesse caso entre produtos e categorias, sendo possível ter um produto que pertence a mais de uma categoria.

---
# 2. Construção de uma API RESTful com Laravel
- Foi criada uma API para atender os requisitois levantados, que seria a listagem de produtos com filtros e a criação/atualização de pedidos.
- Além dos endpoints que atendem esses requisitos, foram criados endpoints básicos (um get e um post) para as demais entidades do banco, para que fosse mais fácil de testar.
- Para rodar o projeto, abrir ele pelo terminal e rodar os comandos:

- 1: Instalar dependências
```bash 
composer install
```

- 2: Criar o arquivo .env
```bash 
cp .env.example .env
```

- 3: Gerar a chave da aplicação
```bash 
php artisan key:generate
```

- 4: Gerar o banco de dados e rodar migrações (escrever 'yes' para a pergunta apresentada)
```bash 
php artisan migrate
```

- 5 (Opcional): Comando para popular o banco com alguns dados para facilitar os testes
```bash 
php artisan db:seed
```

- 6: Gerar o swagger para gerar documentação para visualizar e testar os endpoints
```bash 
php artisan l5-swagger:generate
```

- 7: Rodar o comando para gerar um token, já que está sendo uma validação de autenticação
```bash 
php artisan user:generate-token
```

- 8: Rodar o projeto
```bash 
php artisan serve
```

- O swagger ficará disponível em http://localhost:8000/api/documentation, e nele é possível ver a documentação e realizar testes da API.

- Para que seja possível testar os endpoints, clicar em Authorize e inserir o token que foi gerado na etapa 7.

- Os endpoints principais dos requisitos são:
    - GET /produtos (listar produtos com filtros)
    - E os endpoints com título Pedidos (crud de pedidos)

---
# 3. Lógica e Otimização de Consultas SQL

*1. Escreva uma consulta que retorne o total de pedidos e a receita de cada cliente no último
ano.*

- Uma consulta básica para retornar esses resultados é a seguinte:
```sql
SELECT clientes.nome AS Nome, 
		 COUNT(produtos.id) AS Total_Pedidos, 
		 SUM(produtos.preco * produto_pedido.quantidade) AS Receita
FROM produto_pedido
JOIN produtos on produto_pedido.id_produto = produtos.id
JOIN pedidos on produto_pedido.id_pedido = pedidos.id
JOIN clientes ON pedidos.id_cliente = clientes.id
WHERE YEAR(pedidos.created_at) = YEAR(CURDATE()) - 1
GROUP BY clientes.id
```

- Aqui agrupamos por clientes, contando cada pedido que ele fez, e somando o total de preço (quantidade * preço) de cada produto dos pedidos.

- Uma forma de otimizar essa sql seria por uma CTE. Nessa CTE, buscaríamos primeiro a todos os pedidos do ano anterior, para só depois realizar os joins com as outras tabelas. Isso diminuiria a quantidade de registro que o banco teria que utilizar para realizar os joins, assim otimizando a consulta:
```sql
WITH pedidos_filtrados AS (
    SELECT id,
	       id_cliente,
	       created_at 
    FROM pedidos
    WHERE YEAR(created_at) = YEAR(CURDATE()) - 1
)
SELECT 
    clientes.nome AS Nome, 
    COUNT(produtos.id) AS Total_Pedidos, 
    SUM(produtos.preco * produto_pedido.quantidade) AS Receita
FROM produto_pedido
JOIN produtos ON produto_pedido.id_produto = produtos.id
JOIN pedidos_filtrados ON produto_pedido.id_pedido = pedidos_filtrados.id
JOIN clientes ON pedidos_filtrados.id_cliente = clientes.id
GROUP BY clientes.id
```

*2. Crie uma consulta que mostre os produtos mais vendidos por categoria.*
- A SQl para trazer o resultado é a seguinte:
```sql
SELECT produtos.nome AS Produto, 
		categorias.nome AS Categoria,
	   SUM(produto_pedido.quantidade) AS Qtde_Vendas
FROM produto_pedido
JOIN produtos on produto_pedido.id_produto = produtos.id
JOIN produto_categoria ON produto_categoria.id_produto = produtos.id 
JOIN categorias on produto_categoria.id_categoria = categorias.id
GROUP BY categorias.id, produtos.id
ORDER BY Qtde_Vendas DESC
```

- Aqui agrupamos por categorias e produto, e ordenamos pela quantidade de vendas de forma decrescente. Assim conseguimos visualizar cada produto por cada categoria e quantidade de vendas, deixando aqueles com as maiores vendas primeiro.

- Nessa consulta, como todo join está sendo feito em índices (chaves estrangeiras), acredito que não é possível realizar uma otimizaçãõ direta nessa SQL e da forma que está, dificilmente irá apresentar muita lentidão. O que pode acontecer, é a quantidade de registros começar a impactar na velocidade, nesse caso poderíamos limitar essa consulta (caso fosse utilizada em alguma parte de um sistema, por exemplo), para trazer poucos registros por vez.