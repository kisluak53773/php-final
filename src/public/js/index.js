const searchInput =  document.getElementById('search');
const output = document.getElementById('output');

function debounce(func, delay) {
    let timeoutId;
    return function() {
        const context = this;
        const args = arguments;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}

searchInput.addEventListener('input',  debounce( async () => {
    const value = searchInput.value;
    const response = await fetch(`/order?search=${value}`);
    if (!response.ok){
        window.location.href = '/error';
    }
    const data = await response.json();

    const oldList = document.getElementById('searchList');

    if (oldList) {
        oldList.remove();
    }

    if (data) {
        const list = document.createElement('ul');

        list.className = ' flex justify-center items-center flex-col';
        list.id = 'searchList';

        data.forEach((item) =>{
            const listItem = document.createElement('li');

            listItem.className = 'border-2 border-black border-solid rounded-lg hover:bg-black hover:text-white py-2 px-6 w-1/3 mt-2'

            const anchor = document.createElement('a');
            anchor.href = `/order/${item.id}`;
            anchor.textContent = item.product;

            listItem.appendChild(anchor);
            list.appendChild(listItem);
        })

        output.insertAdjacentElement('afterend', list);
    }
}, 500))