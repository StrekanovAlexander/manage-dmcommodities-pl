import React, { useState, useEffect } from 'react'
import Table from './components/Table/Table.jsx'
  
function App() { 
    // const basePricesUrl = 'http://manage-dmcommodities-pl.loc/base-prices/json'
    // const productPricesUrl = 'http://manage-dmcommodities-pl.loc/product-prices/json'
    const basePricesUrl = 'https://manage.dmcommodities.pl/base-prices/json'
    const productPricesUrl = 'https://manage.dmcommodities.pl/product-prices/json'

    const [ basePrices, setBasePrices ] = useState([])
    const [ productPrices, setProductPrices ] = useState([])

    useEffect(() => {
        fetch(basePricesUrl).then(res => res.json()).then(data => setBasePrices(data))
        fetch(productPricesUrl).then(res => res.json()).then(data => setProductPrices(data))
    }, [])

    function changeBasePrices(id, price) {
        const changedBasePrices = basePrices.map(el => {
            if (id == el.id) {
                return { ...el, price: price }
            }
            return el
        })
        setBasePrices(changedBasePrices)

        const updatedProductPrices = productPrices.map(productPrice => {
            const updatedProducts = productPrice.products.map(product => {
                if (product.id == id) {
                    const newPrice = Number(productPrice.price) + price
                    return { ...product, price: newPrice }
                }
                return product
            })
            return {...productPrice, products: updatedProducts}
        })
        setProductPrices(updatedProductPrices) 
    }

    function changeProductPrices(id, price) {
        const changedProductPrices = productPrices.map(productPrice => {
            if (productPrice.id == id) {
                const products = productPrice.products.map(product => {
                    const base = basePrices.filter(el => el.id == product.id) 
                    const newPrice = Number(base[0].price) + price
                    return { ...product, price: newPrice }
                })
                return { ...productPrice, price: price, products: products }
            }
            return productPrice
        })
        setProductPrices(changedProductPrices)
    }

    return (
        <div>
            <Table 
                basePrices={basePrices} 
                changeBasePrices={changeBasePrices} 
                productPrices={productPrices}
                changeProductPrices={changeProductPrices}
            />
        </div>
    )
}

export default App