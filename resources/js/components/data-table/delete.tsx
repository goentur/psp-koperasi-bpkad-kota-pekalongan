import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { CheckCheck, Loader2, X } from 'lucide-react'
interface props {
    open: boolean
    setOpen: (value: boolean) => void
    processing: boolean
    handleHapusData: (e: React.FormEvent) => void
}
export default function Delete({
    open,
    setOpen,
    processing,
    handleHapusData,
}: props) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle className="text-xl">
                        Apakah Anda yakin?
                    </DialogTitle>
                    <DialogDescription className="text-justify">
                        Setelah data Anda dihapus, data tidak bisa di kembalikan lagi.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="sm:justify-start gap-4">
                    <Button type="button" onClick={() => {setOpen(false)}}> <X /> Tidak</Button>
                    <Button type="button" variant="destructive" onClick={handleHapusData} disabled={processing}>
                        {processing ? (<Loader2 className="animate-spin" />) : (<CheckCheck />)} Ya
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
