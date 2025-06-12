import { usePage } from '@inertiajs/react';
import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
    const { name }: any = usePage().props
    return (
        <>
            <AppLogoIcon className="w-12" />
            <div className="grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-none font-semibold">
                    {name}
                    <br />
                    KOTA PEKALONGAN
                </span>
            </div>
        </>
    );
}
