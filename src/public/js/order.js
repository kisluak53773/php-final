import { cartService } from "./api/cart.js";

const cart = document.getElementById("cart");
let cartProducts = [];
const CLOSE_ICON = "/public/svg/close.svg";

console.log(cartProducts);

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

    priceContainer.appendChild(div);

    cart.appendChild(list);
    cart.appendChild(priceContainer);
  }
};

document.addEventListener("DOMContentLoaded", async () => {
  cartProducts = await cartService.getCartProducts();

  renderCartProducts();
});
