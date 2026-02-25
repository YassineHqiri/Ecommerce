import { Routes, Route } from 'react-router-dom';
import Layout from '../components/layout/Layout';
import Home from '../pages/public/Home';
import Categories from '../pages/public/Categories';
import ProductDetail from '../pages/public/ProductDetail';
import About from '../pages/public/About';
import Contact from '../pages/public/Contact';
import OrderForm from '../pages/public/OrderForm';
import PrivacyPolicy from '../pages/public/PrivacyPolicy';
import Terms from '../pages/public/Terms';
import FAQ from '../pages/public/FAQ';

const PublicRoutes = () => {
  return (
    <Routes>
      <Route element={<Layout />}>
        <Route index element={<Home />} />
        <Route path="categories" element={<Categories />} />
        <Route path="product/:slug" element={<ProductDetail />} />
        <Route path="about" element={<About />} />
        <Route path="contact" element={<Contact />} />
        <Route path="order" element={<OrderForm />} />
        <Route path="order/:packId" element={<OrderForm />} />
        <Route path="privacy" element={<PrivacyPolicy />} />
        <Route path="terms" element={<Terms />} />
        <Route path="faq" element={<FAQ />} />
      </Route>
    </Routes>
  );
};

export default PublicRoutes;
