
const img = document.querySelector('img');
img.setAttribute('src', 'image.jpg');
const src = img.getAttribute('src');
img.removeAttribute('alt');

const box = document.getElementById('box');
box.style.backgroundColor = 'blue';
box.style.width = '200px';

const div = document.querySelector('.myDiv');
div.classList.add('active');
div.classList.toggle('hidden');

const newDiv = document.createElement('div');
newDiv.textContent = 'Hello, World!';

const parent = document.getElementById('parent');
parent.appendChild(newDiv);

parent.removeChild(newDiv);

const button = document.querySelector('button');
button.addEventListener('click', () => {
alert('Button clicked!');
});

const handleClick = () => alert('Clicked!');
button.addEventListener('click', handleClick);
button.removeEventListener('click', handleClick);

const parent = document.getElementById('parent');
console.log(parent.firstChild);
console.log(parent.lastChild);

console.log(parent.firstElementChild);
console.log(parent.lastElementChild);
