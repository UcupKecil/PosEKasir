import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Buy extends Component {
    constructor(props) {
        super(props);
        this.state = {
            buy: [],
            products: [],
            kategoris: [],
            supliers: [],
            barcode: "",
            search: "",
            kategori_id: "",
            suplier_id: ""
        };

        this.loadBuy = this.loadBuy.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyBuy = this.handleEmptyBuy.bind(this);
        this.loadKategoris = this.loadKategoris.bind(this);
        this.loadProducts = this.loadProducts.bind(this);

        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleChangeKategori = this.handleChangeKategori.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setSuplierId = this.setSuplierId.bind(this);
        this.handleClickKategori = this.handleClickKategori.bind(this)
        this.handleClickSubmit = this.handleClickSubmit.bind(this)
    }



    componentDidMount() {
        // load user buy
        this.loadBuy();
        this.loadProducts();
        this.loadKategoris();
        this.loadSupliers();
    }

    loadSupliers() {
        axios.get(`/admin/supliers`).then(res => {
            const supliers = res.data;
            this.setState({ supliers });
        });
    }

    loadProducts(search = "",kategori = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then(res => {
            const products = res.data.data;
            this.setState({ products });
        });


    }



    loadKategoris(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/kategoris${query}`).then(res => {
            const kategoris = res.data.data;
            this.setState({ kategoris });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadBuy() {
        axios.get("/admin/buy").then(res => {
            const buy = res.data;
            this.setState({ buy });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/buy", { barcode })
                .then(res => {
                    this.loadBuy();
                    this.setState({ barcode: "" });
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const buy = this.state.buy.map(c => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ buy });

        axios
            .post("/admin/buy/change-qty", { product_id, quantity: qty })
            .then(res => { })
            .catch(err => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(buy) {
        const total = buy.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }

    handleClickDelete(product_id) {
        axios
            .post("/admin/buy/delete", { product_id, _method: "DELETE" })
            .then(res => {
                const buy = this.state.buy.filter(c => c.id !== product_id);
                this.setState({ buy });
            });
    }

    handleEmptyBuy() {
        axios.post("/admin/buy/empty", { _method: "DELETE" }).then(res => {
            this.setState({ buy: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }

    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    handleChangeKategori(kategori_id) {
        const kategori = kategori_id;
        this.setState({ kategori });
        console.log(kategori);
    }

    handleClickKategori(kategori_id) {

        this.loadProducts(kategori_id);
    }

    addProductToBuy(barcode) {
        let product = this.state.products.find(p => p.barcode === barcode);
        if (!!product) {
            // if product is already in buy
            let buy = this.state.buy.find(c => c.id === product.id);
            if (!!buy) {
                // update quantity
                this.setState({
                    buy: this.state.buy.map(c => {
                        if (c.id === product.id && product.quantity > c.pivot.quantity) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    })
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1
                        }
                    };

                    this.setState({ buy: [...this.state.buy, product] });
                }
            }

            axios
                .post("/admin/buy", { barcode })
                .then(res => {
                    // this.loadBuy();
                    console.log(res);
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setSuplierId(event) {
        this.setState({ suplier_id: event.target.value });
    }


    handleClickSubmit() {
        Swal.fire({
            title: 'Pembelian',
            input: 'text',
            inputValue: this.getTotal(this.state.buy),
            showCancelButton: true,
            confirmButtonText: 'Send',
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                return axios.post('/admin/pembelians', { suplier_id: this.state.suplier_id, amount }).then(res => {
                    this.loadBuy();
                    return res.data;
                }).catch(err => {
                    Swal.showValidationMessage(err.response.data.message)
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.value) {
                //
            }
        })

    }
    render() {
        const { buy, products,kategoris, supliers, barcode } = this.state;
        return (
            <div className="row">
                <div className="col-md-6 col-lg-4">
                    <div className="row mb-2">
                        <div className="col">
                            <form onSubmit={this.handleScanBarcode}>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="Scan Barcode..."
                                    value={barcode}
                                    onChange={this.handleOnChangeBarcode}
                                />
                            </form>
                        </div>
                        <div className="col">
                        <select
                                className="form-control"
                                onChange={this.setSuplierId}
                            >
                                <option value="">Walking Suplier</option>
                                {supliers.map(cus => (
                                    <option
                                        key={cus.id}
                                        value={cus.id}
                                    >{`${cus.name} `}</option>
                                ))}
                            </select>
                        </div>
                    </div>
                    <div className="user-buy">
                        <div className="card">
                            <table className="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th className="text-right">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {buy.map(c => (
                                        <tr key={c.id}>
                                            <td>{c.name}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm qty"
                                                    value={c.pivot.quantity}
                                                    onChange={event =>
                                                        this.handleChangeQty(
                                                            c.id,
                                                            event.target.value
                                                        )
                                                    }
                                                />
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() =>
                                                        this.handleClickDelete(
                                                            c.id
                                                        )
                                                    }
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            <td className="text-right">
                                                {window.APP.currency_symbol}{" "}
                                                {(
                                                    c.price * c.pivot.quantity
                                                ).toFixed(2)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Total:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol} {this.getTotal(buy)}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-danger btn-block"
                                onClick={this.handleEmptyBuy}
                                disabled={!buy.length}
                            >
                                Cancel
                            </button>
                        </div>
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-primary btn-block"
                                disabled={!buy.length}
                                onClick={this.handleClickSubmit}
                            >
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 col-lg-8">
                    <div className="mb-2">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="Search Product..."
                            onChange={this.handleChangeSearch}
                            onKeyDown={this.handleSeach}
                        />
                    </div>


                    <div className="kategori-product">
                        {kategoris.map(k => (
                            <div


                                key={k.id}
                                className="item"
                            >

                                <button
                                type="button"
                                className="btn btn-primary btn-block"





                                onClick={() =>
                                    this.handleClickKategori(
                                        k.id
                                    )

                                }
                            >
                                {k.name}
                                <br></br>
                                {k.id}
                            </button>

                            </div>
                        ))}
                    </div>
                    <br></br>
                    <br></br>
                    <br></br>
                    <br></br>


                    <div className="order-product">
                        {products.map(p => (
                            <div
                                onClick={() => this.addProductToBuy(p.barcode)}
                                key={p.id}
                                className="item"
                            >
                                <img src={p.image_url} alt="" />
                                <h5 style={window.APP.warning_quantity > p.quantity ? { color: 'red' } : {}}>{p.name}({p.quantity})</h5>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        );
    }
}

export default Buy;

if (document.getElementById("buy")) {
    ReactDOM.render(<Buy />, document.getElementById("buy"));
}
