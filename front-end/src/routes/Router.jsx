import React, {Suspense, lazy, useEffect} from 'react';
import {Routes, Route, useLocation} from 'react-router-dom';
import Services from "../pages/Services/Services.jsx";
import Loader from "../component/laoder/Loader.jsx";
import Register from "../pages/Register/Register.jsx";
import Auth from "../pages/Auth/Auth.jsx";
import HoneyModuleDetail from "../pages/HoneyModuleDetail/HoneyModuleDetail.jsx";
import PlantBreedingModuleDetail from "../pages/PlantBreedingModuleDetail/PlantBreedingModuleDetail.jsx";
import MilkModuleDetails from "../pages/MilkModuleDetail/MilkModuleDetails.jsx";
import Profile from "../pages/Profile/Profile.jsx";

const Home = lazy(() => import('../pages/Home/Home.jsx'));
const AboutUs = lazy(() => import('../pages/About/AboutUs.jsx'));
const NotFound = lazy(() => import('../pages/NotFound/NotFound.jsx'));

const Router = () => {
    const location = useLocation();

    useEffect(() => {
        window.scrollTo(0, 0);
    }, [location.pathname]);
    return (
        <Suspense fallback={<Loader />}>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/about" element={<AboutUs />} />
                <Route path="/modules" element={<Services />} />
                <Route path="/register" element={<Register />} />
                <Route path="/profile" element={<Profile />} />
                <Route path="/authentication" element={<Auth />} />
                <Route path="/details/honey" element={<HoneyModuleDetail />} />
                <Route path="/details/plant" element={<PlantBreedingModuleDetail />} />
                <Route path="/details/milk" element={<MilkModuleDetails />} />
                <Route path="*" element={<NotFound />} />
            </Routes>
        </Suspense>
    );
};

export default Router;
