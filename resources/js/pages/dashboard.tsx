import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { Banknote, DollarSignIcon, IdCardIcon, PiggyBankIcon } from 'lucide-react';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: 'dashboard',
    },
];

export default function Dashboard() {
    const [loadingDataDashboard, setLoadingDataDashboard] = useState(false)
    const [loadingDataAnggota, setLoadingDataAnggota] = useState(false)
    const [dataAnggota, setDataAnggota] = useState<[]>([])
    const [dataDashboard, setDataDashboard] = useState({
        kas : 0,
        pinjaman : 0,
        simpanan : 0,
        bank : 0,
    })
    const getDataDashboard = async () => {
        setLoadingDataDashboard(true)
        try {
            const response = await axios.post(route('data-dashboard'))
            setDataDashboard(response.data)
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoadingDataDashboard(false)
        }
    }
    const getDataAnggota = async () => {
        setLoadingDataAnggota(true)
        try {
            const response = await axios.post(route('data-anggota-dashboard'))
            setDataAnggota(response.data)
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoadingDataAnggota(false)
        }
    }
    useEffect(() => {
        getDataDashboard()
        getDataAnggota()
    },[])
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-4">
                    <div className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">KAS</h3>
                                <p className="text-2xl font-bold text-green-700 dark:text-green-400 mt-1">
                                Rp. {!loadingDataDashboard? dataDashboard?.kas:'...'}
                                </p>
                            </div>
                            <div className="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <span className="text-green-600 dark:text-green-400 text-xs"><DollarSignIcon/></span>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">SIMPANAN</h3>
                                <p className="text-2xl font-bold text-blue-700 dark:text-blue-400 mt-1">
                                Rp. {!loadingDataDashboard? dataDashboard?.simpanan:'...'}
                                </p>
                            </div>
                            <div className="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <span className="text-blue-600 dark:text-blue-400 text-xs"><PiggyBankIcon/></span>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">PINJAMAN</h3>
                                <p className="text-2xl font-bold text-red-700 dark:text-red-400 mt-1">
                                Rp. {!loadingDataDashboard? dataDashboard?.pinjaman:'...'}
                                </p>
                            </div>
                            <div className="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                                <span className="text-red-600 dark:text-red-400 text-xs"><IdCardIcon/></span>
                            </div>
                        </div>
                    </div>
                    <div className="bg-white dark:bg-gray-800 p-5 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="text-sm font-medium text-gray-500 dark:text-gray-400">BANK</h3>
                                <p className="text-2xl font-bold text-purple-700 dark:text-purple-400 mt-1">
                                Rp. {!loadingDataDashboard? dataDashboard?.bank:'...'}
                                </p>
                            </div>
                            <div className="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                <span className="text-purple-600 dark:text-purple-400 text-xs"><Banknote/></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                <table className="w-full text-left border-collapse border">
                    <thead>
                        <tr className="uppercase text-sm leading-normal">
                            <th className="p-2 border w-[1px]">NO</th>
                            <th className="p-2 border">NIK</th>
                            <th className="p-2 border">NAMA</th>
                            <th className="p-2 border">BIDANG</th>
                            <th className="p-2 border w-1">SIMPANAN</th>
                            <th className="p-2 border w-1">PINJAMAN</th>
                        </tr>
                    </thead>
                    <tbody className="font-light">
                        {loadingDataAnggota && <LoadingData colSpan={5}/>}
                        {dataAnggota.length > 0 ? (
                            dataAnggota.map((value: any, index: number) => (
                                <tr
                                    key={index}
                                    className="hover:bg-gray-100 dark:hover:bg-slate-900 align-text-top"
                                >
                                    <td className="px-2 py-1 border text-center">{++index}</td>
                                    <td className="px-2 py-1 w-1 border">{value?.nik}</td>
                                    <td className="px-2 py-1 border">{value?.nama}</td>
                                    <td className="px-2 py-1 border">{value?.satuan_kerja?.nama}</td>
                                    <td className="px-2 py-1 border text-end">{value?.total_simpanan_all}</td>
                                    <td className="px-2 py-1 border text-end">{value?.total_pinjaman_all}</td>
                                </tr>
                            ))
                        ) : (!loadingDataAnggota ?<NoData colSpan={5}/>: null)}
                    </tbody>
                </table>
                </div>
            </div>
        </AppLayout>
    );
}
