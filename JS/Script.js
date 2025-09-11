document.addEventListener('DOMContentLoaded', () => {
  // Ajuste o seletor conforme seu HTML (ex.: '#cadastrado', '.msg-sucesso')
  const aviso = document.querySelector('.sucesso');

  if (aviso) {
    setTimeout(() => {
      aviso.classList.add('oculto'); // classe já existente no seu CSS
    }, 6000); // 8 segundos
  }
});