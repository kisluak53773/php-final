import { cartService } from "./api/cart.js";
import { productService } from "./api/product.js";

const searchInput = document.getElementById("search");
const output = document.getElementById("output");
const content = document.getElementById("content");
const cart = document.getElementById("cart");
const CLOSE_ICON = "/public/svg/close.svg";
let cartProducts = [];

function debounce(func, delay) {
  let timeoutId;
  return function () {
    const context = this;
    const args = arguments;

    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func.apply(context, args);
    }, delay);
  };
}

const hadleProductDelete = async (productId) => {
  const response = await cartService.deleteProductFromCart(productId);

  if (response.ok) {
    const newCartProducts = cartProducts.filter(
      (item) => item.id !== productId
    );
    cartProducts = [...newCartProducts];
    renderCartProducts();
  }
};

const renderCartProducts = () => {
  const oldList = document.getElementById("cartProducts");
  const oldPriceContainer = document.getElementById("priceContainer");

  if (oldList) {
    oldList.remove();
  }

  if (oldPriceContainer) {
    oldPriceContainer.remove();
  }

  if (cartProducts) {
    const list = document.createElement("ul");
    list.id = "cartProducts";

    cartProducts.forEach((item) => {
      const listItem = document.createElement("li");
      listItem.className =
        " border-b-2 group/cartItem border-b-black border-b-solid border-t-black border-t-2" +
        "border-t-solid flex items-center p-2";

      const title = document.createElement("h1");
      title.textContent = item.name;
      title.className = "font-bold";

      const span = document.createElement("span");
      span.textContent = "Price:" + item.price;
      span.className = "ml-4";

      const img = document.createElement("img");
      img.src = CLOSE_ICON;

      const button = document.createElement("button");
      button.appendChild(img);
      button.className =
        " ml-auto w-4 h-4 cursor-pointer mr-2 invisible group-hover/cartItem:visible";
      button.addEventListener("click", () => hadleProductDelete(item.id));

      listItem.appendChild(title);
      listItem.appendChild(span);
      listItem.appendChild(button);
      list.appendChild(listItem);
    });

    const totalPrice = cartProducts.reduce(
      (acc, item) => (acc += Number(item.price)),
      0
    );

    const priceContainer = document.createElement("div");
    priceContainer.className = "h-full me-auto p-4 flex";
    priceContainer.id = "priceContainer";

    const div = document.createElement("div");
    div.innerText = "Total price: " + totalPrice;

    const button = document.createElement("button");
    button.textContent = "Оформить заказ";
    button.className =
      "rounded-lg bg-black text-white py-2 px-4 h-10 ml-auto transition-all hover:bg-gray-700";
    button.addEventListener(
      "click",
      () => (window.location.href = "/order/page")
    );

    priceContainer.appendChild(div);
    priceContainer.appendChild(button);

    cart.appendChild(list);
    cart.appendChild(priceContainer);
  }
};

const handleCart = async (product) => {
  if (cartProducts) {
    const itemIsInCart = cartProducts.find((item) => item.id === product.id);

    if (!itemIsInCart) {
      const response = await cartService.addProductToCart(product);

      if (response.ok) {
        cartProducts = [...cartProducts, product];
        renderCartProducts();
      }
    }
  } else {
    const response = await cartService.addProductToCart(product);

    if (response.ok) {
      cartProducts = [product];
    }
  }
};

document.addEventListener("DOMContentLoaded", async () => {
  const data = await productService.getProducts();
  cartProducts = await cartService.getCartProducts();

  renderCartProducts();

  if (data) {
    const list = document.createElement("ul");
    list.id = "productList";
    list.className = " grid grid-cols-3 gap-6 px-4 w-2/3";

    data.forEach((item) => {
      const listItem = document.createElement("li");

      listItem.className =
        "border-2 border-black border-solid rounded-lg py-2 px-6 flex flex-col text-center";

      const name = document.createElement("span");
      name.textContent = item.name;
      name.className = " my-6";

      const price = document.createElement("div");
      price.textContent = "Price: " + item.price;
      price.className = "mb-2";

      const button = document.createElement("button");
      button.textContent = "Добавить в корзину";
      button.className =
        "rounded-lg bg-black text-white py-2 px-4 transition-all hover:bg-gray-700 mb-4";
      button.addEventListener("click", () => handleCart(item));

      listItem.appendChild(name);
      listItem.appendChild(price);
      listItem.appendChild(button);
      list.appendChild(listItem);
    });

    content.prepend(list);
  }
});

searchInput.addEventListener(
  "input",
  debounce(async () => {
    const value = searchInput.value;
    const oldList = document.getElementById("searchList");

    if (oldList) {
      oldList.remove();
    }

    if (value) {
      const data = await productService.getProductsByName(value);

      if (data) {
        const list = document.createElement("ul");

        list.className =
          "absolute w-1/3 left-1/3 z-10 p-4 rounded-lg drop-shadow-md mt-2 bg-white max-h-52 overflow-auto flex justify-center items-center flex-col";
        list.id = "searchList";

        data.forEach((item) => {
          const listItem = document.createElement("li");

          listItem.className =
            " border-2 border-black border-solid rounded-lg hover:bg-black " +
            "hover:text-white py-2 px-6 w-full mt-2 transition-all";

          const button = document.createElement("button");
          button.textContent = item.name + " Price: " + item.price;
          button.addEventListener("click", () => handleCart(item));

          listItem.appendChild(button);
          list.appendChild(listItem);
        });

        output.insertAdjacentElement("afterend", list);
      }
    }
  }, 500)
);
