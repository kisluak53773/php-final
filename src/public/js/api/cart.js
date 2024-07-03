const BASE_URL = "/cart";

export const cartService = {
  async getCartProducts() {
    const response = await fetch(`${BASE_URL}/product`);

    if (!response.ok) {
      window.location.href = "/error";
    }

    const data = await response.json();

    return data;
  },

  async addProductToCart(product) {
    const formData = new URLSearchParams();
    formData.append("productId", product.id);

    const response = await fetch(`${BASE_URL}/add`, {
      method: "POST",
      body: formData,
    });

    return response;
  },

  async deleteProductFromCart(productId) {
    const response = await fetch(`${BASE_URL}/product/${productId}`, {
      method: "DELETE",
    });

    return response;
  },
};
