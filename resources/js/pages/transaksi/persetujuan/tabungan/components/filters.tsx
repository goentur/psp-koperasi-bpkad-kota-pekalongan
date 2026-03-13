import Combobox from '@/components/combobox'
import ComboboxDinamis from '@/components/combobox-dinamis'
import { Button } from '@/components/ui/button'
import axios from 'axios'
import { Loader2, Search } from 'lucide-react'
import { useEffect, useState } from 'react'

type props = {
    data: any
    setInfoDataTabel: (data: any) => void
    simpanans: any
}

export default function Filters({
    data,
    setInfoDataTabel,
    simpanans,
}: props) {
    const [anggota, setAnggota] = useState("");
    useEffect(() => {
        setAnggota("")
    }, [open])
    return (
        <div className="mb-4 flex w-full items-end gap-4">
            <div className="flex-1">
                <ComboboxDinamis
                    label="anggota"
                    selectedValue={data.anggota}
                    fetchOptions={async (search) => {
                        const { data: options } = await axios.post(route('anggota.list'), { id: data.anggota, search });
                        if (!options.find((d: any) => d.value === data.anggota) && search !== '') {
                            setInfoDataTabel((prev: any) => ({ ...prev, anggota: '', namaAnggota: '' }));
                        }
                        return options;
                    }}
                    onSelect={(value, label) => {setInfoDataTabel((prev: any) => ({ ...prev, anggota: value, namaAnggota:label })); setAnggota(label)}}
                    search={anggota}
                    setSearch={setAnggota}
                />
            </div>
            <div className="flex-1">
                <Combobox label="simpanan" selectedValue={data.simpanan} options={simpanans} onSelect={(value) => setInfoDataTabel((prevData:any) => ({ ...prevData, simpanan: value }))} />
            </div>
        </div>
    )
}
