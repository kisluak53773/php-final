const BASE_URL = "/product";

export const productService = {
  async getProducts() {
    const response = await fetch(BASE_URL);

    if (!response.ok) {
      window.location.href = "/error";
    }

    const data = await response.json();

    return data;
  },

  async getProductsByName(name) {
    const response = await fetch(`${BASE_URL}?search=${name}`);

    if (!response.ok) {
      window.location.href = "/error";
    }

    const data = await response.json();

    return data;
  },
};
