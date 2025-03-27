import Manager from "./Manager";
import Modal from '@stiumentsev/modal';
import GTM from "./GTM";
import Storage from "./Storage";
import '@stiumentsev/modal/src/style.scss';
import "./sass/style.scss"
import EmbedBlocker from "./EmbedBlocker";

Modal.start();

GTM.start(Storage);
Manager.start(Storage);
EmbedBlocker.start(Storage);