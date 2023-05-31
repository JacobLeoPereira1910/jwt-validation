const btnShow = document.getElementById('show-eye');
const btnHide = document.getElementById('hide-eye');
const pwd = document.getElementById('password');

btnShow.addEventListener('click', () => {
  pwd.type = 'text';
  btnShow.classList.add('hidden');
  btnHide.classList.remove('hidden');
});

btnHide.addEventListener('click', () => {
  pwd.type = 'password';
  btnHide.classList.add('hidden');
  btnShow.classList.remove('hidden');
});

const form = document.querySelector("#form");

form.addEventListener('submit', async function (event) {
  event.preventDefault();

  const formData = new FormData(event.target);
  const url = 'https://localhost/scsp/public/login.php';

  const data = {
    email: formData.get('email'),
    senha: formData.get('senha')
  };

  try {
    const response = await fetch(url, {
      method: 'POST',
      body: JSON.stringify(data)
    });

    if (!response.ok) {
      throw new Error('Erro na requisição');
    }

    const responseData = await response.json();
    console.log(responseData);
    // Faça algo com a resposta da requisição
  } catch (error) {
    console.error(error);
    // Trate o erro adequadamente
  }
});