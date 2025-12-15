# 🛠️ Sistema de Gestão de Estoque – SAEP DB

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white">
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">
</p>

Sistema web simples e funcional para gerenciar o estoque de ferramentas em um almoxarifado. Desenvolvido para atender aos requisitos da avaliação do SENAI, o projeto inclui autenticação de usuários, cadastro de produtos, registro de movimentações (entrada/saída) e alertas automáticos de estoque mínimo.

---

## ✨ Funcionalidades

- 🔐 **Login de usuários** com e-mail e senha
- ➕ **Cadastro de produtos** (nome, tipo, código, estoque mínimo e atual)
- 📊 **Gestão de estoque** com listagem completa de produtos
- 🔍 **Busca por nome, tipo ou código**
- 📥 **Entrada de estoque** (adicionar quantidade)
- 📤 **Saída de estoque** (retirar quantidade)
- ⚠️ **Alerta visual** quando o estoque cai abaixo do mínimo
- 📜 **Histórico completo** de movimentações (quem fez, o quê, quando)

---

## 🗃️ Tecnologias Utilizadas

- **Backend**: PHP 8.1
- **Banco de Dados**: MySQL 8.0
- **Frontend**: HTML5, CSS3, JavaScript (vanilla)
- **Servidor Local**: Apache (via XAMPP)
- **IDE**: VS Code

---

## 🚀 Como Rodar Localmente

### Pré-requisitos
- [XAMPP](https://www.apachefriends.org/pt_br/index.html) instalado (ou outro ambiente com Apache + MySQL)
- Navegador web moderno (Chrome, Firefox, Edge)

### Passos

1. **Clone este repositório**:
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git

---

## 📝 Observações Importantes
Este é um projeto acadêmico. Em um ambiente de produção real, seria necessário:
-Hash de senhas (em vez de armazenar senhas em texto claro)
-Filtros de entrada e proteção contra SQL injection (já parcialmente implementado com PDO)
-Validação mais robusta no backend
-Sistema de logout seguro
O script SQL inclui dados reais de exemplo (ferramentas de almoxarifado).
